<?php

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /** Business hours constants. */
    private const OPEN_HOUR = 9;

    private const CLOSE_HOUR = 21;

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $calendarMonth = CarbonImmutable::createFromFormat(
            'Y-m-d',
            ($validated['month'] ?? now()->format('Y-m')).'-01'
        )->startOfMonth();

        $appointments = Appointment::with('service')
            ->whereBelongsTo($request->user())
            ->whereBetween('appointment_at', [$calendarMonth, $calendarMonth->endOfMonth()])
            ->oldest('appointment_at')
            ->get();

        $upcomingAppointments = Appointment::with('service')
            ->whereBelongsTo($request->user())
            ->where('appointment_at', '>=', now())
            ->whereNotIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::Completed])
            ->oldest('appointment_at')
            ->limit(4)
            ->get();

        return view('client.appointments.index', compact('appointments', 'calendarMonth', 'upcomingAppointments'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('client.appointments.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', Rule::exists(Service::class, 'id')->where('is_active', true)],
            'appointment_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $appointmentAt = CarbonImmutable::parse($validated['appointment_at']);

        // Enforce business hours (09:00–21:00, 7 days a week)
        $startMinutes = $appointmentAt->hour * 60 + $appointmentAt->minute;
        $endMinutes = $startMinutes + $service->duration;
        $openMinutes = self::OPEN_HOUR * 60;
        $closeMinutes = self::CLOSE_HOUR * 60;

        if ($startMinutes < $openMinutes || $endMinutes > $closeMinutes) {
            return back()->withErrors(['appointment_at' => 'The appointment must fit within business hours (09:00–21:00).'])->withInput();
        }

        // Prevent overlapping bookings (database-agnostic: load and check in PHP)
        $appointmentEnd = $appointmentAt->addMinutes($service->duration);
        $existingBookings = Appointment::with('service')
            ->whereDate('appointment_at', $appointmentAt->toDateString())
            ->whereNotIn('status', [AppointmentStatus::Cancelled])
            ->get();

        $hasOverlap = $existingBookings->contains(function (Appointment $existing) use ($appointmentAt, $appointmentEnd) {
            $existingStart = $existing->appointment_at;
            $existingEnd = $existing->appointment_at->addMinutes($existing->service->duration);

            return $appointmentAt->lt($existingEnd) && $appointmentEnd->gt($existingStart);
        });

        if ($hasOverlap) {
            return back()->withErrors(['appointment_at' => 'This time slot is already booked. Please choose another.'])->withInput();
        }

        $appointment = $request->user()->appointments()->create([
            ...$validated,
            'status' => AppointmentStatus::Pending,
        ]);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment booked.');
    }

    public function show(Request $request, Appointment $appointment): View
    {
        $this->authorizeOwner($request, $appointment);
        $appointment->load('service');

        return view('client.appointments.show', compact('appointment'));
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeOwner($request, $appointment);
        abort_unless(in_array($appointment->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed], true), 422);
        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        return back()->with('success', 'Appointment cancelled.');
    }

    /**
     * Return available time slots for a given date and service as JSON.
     *
     * Generates 30-minute interval slots within business hours, then marks
     * each slot as unavailable if it would overlap with an existing booking
     * (accounting for service durations).
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'service_id' => ['required', Rule::exists(Service::class, 'id')->where('is_active', true)],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $date = $validated['date'];
        $dateCarbon = CarbonImmutable::parse($date);

        // Get all non-cancelled bookings for this date with their service durations
        $existingBookings = Appointment::with('service')
            ->whereDate('appointment_at', $date)
            ->whereNotIn('status', [AppointmentStatus::Cancelled])
            ->get()
            ->map(fn (Appointment $appointment): array => [
                'start' => $appointment->appointment_at->hour * 60 + $appointment->appointment_at->minute,
                'end' => $appointment->appointment_at->hour * 60 + $appointment->appointment_at->minute + $appointment->service->duration,
            ]);

        // Last bookable slot: close_hour minus service duration
        $lastSlotMinutes = (self::CLOSE_HOUR * 60) - $service->duration;

        // Build a set of candidate slot times (in minutes from midnight)
        // Start with standard 30-minute grid
        $candidateMinutes = collect();
        for ($m = self::OPEN_HOUR * 60; $m <= $lastSlotMinutes; $m += 30) {
            $candidateMinutes->push($m);
        }

        // Add dynamic catch-up slots: right after each booking ends
        // (rounded up to the next 5-minute mark for clean scheduling)
        foreach ($existingBookings as $booking) {
            $endRounded = (int) (ceil($booking['end'] / 5) * 5);
            if ($endRounded >= self::OPEN_HOUR * 60 && $endRounded <= $lastSlotMinutes) {
                $candidateMinutes->push($endRounded);
            }
        }

        // Deduplicate and sort
        $candidateMinutes = $candidateMinutes->unique()->sort()->values();

        $slots = $candidateMinutes
            ->map(function (int $minutes) use ($existingBookings, $service, $dateCarbon): array {
                $slotEnd = $minutes + $service->duration;

                // Check overlap with any existing booking
                $isBooked = $existingBookings->contains(
                    fn (array $booking): bool => $minutes < $booking['end'] && $slotEnd > $booking['start']
                );

                // If today, reject past slots
                $isPast = $dateCarbon->isToday() && $minutes <= now()->hour * 60 + now()->minute;

                $h = intdiv($minutes, 60);
                $m = $minutes % 60;

                return [
                    'time' => sprintf('%02d:%02d', $h, $m),
                    'available' => ! $isBooked && ! $isPast,
                ];
            })
            ->values();

        return response()->json([
            'date' => $date,
            'service' => $service->name,
            'duration' => $service->duration,
            'slots' => $slots,
        ]);
    }

    private function authorizeOwner(Request $request, Appointment $appointment): void
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);
    }
}
