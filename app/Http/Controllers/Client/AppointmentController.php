<?php

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
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

    public function availableSlots(Request $request): View
    {
        $validated = $request->validate(['date' => ['required', 'date', 'after_or_equal:today']]);
        $bookedSlots = Appointment::whereDate('appointment_at', $validated['date'])
            ->whereNotIn('status', [AppointmentStatus::Cancelled])
            ->pluck('appointment_at')
            ->map(fn ($date) => $date->format('H:i'));

        $availableSlots = collect(CarbonPeriod::create("{$validated['date']} 09:00", '30 minutes', "{$validated['date']} 18:00"))
            ->map(fn ($slot) => $slot->format('H:i'))
            ->reject(fn (string $slot) => $bookedSlots->contains($slot))
            ->values();

        return view('client.appointments.available-slots', compact('availableSlots'));
    }

    private function authorizeOwner(Request $request, Appointment $appointment): void
    {
        abort_unless($appointment->user_id === $request->user()->id, 403);
    }
}
