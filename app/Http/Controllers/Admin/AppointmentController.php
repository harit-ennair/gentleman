<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
            'month' => ['nullable', 'date_format:Y-m'],
            'status' => ['nullable', Rule::enum(AppointmentStatus::class)],
        ]);
        $calendarMonth = CarbonImmutable::createFromFormat(
            'Y-m-d',
            ($validated['month'] ?? now()->format('Y-m')).'-01'
        )->startOfMonth();
        $selectedDate = isset($validated['date'])
            ? CarbonImmutable::createFromFormat('Y-m-d', $validated['date'])->startOfDay()
            : ($calendarMonth->isCurrentMonth() ? CarbonImmutable::today() : $calendarMonth);

        $appointments = Appointment::with(['user', 'service'])
            ->whereBetween('appointment_at', [$calendarMonth, $calendarMonth->endOfMonth()])
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->oldest('appointment_at')
            ->get();

        $nextAppointments = Appointment::with(['user', 'service'])
            ->where('appointment_at', '>=', now())
            ->whereNotIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::Completed])
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->oldest('appointment_at')
            ->limit(5)
            ->get();

        $dailyAppointments = Appointment::with(['user', 'service'])
            ->whereBetween('appointment_at', [$selectedDate, $selectedDate->endOfDay()])
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->oldest('appointment_at')
            ->get();

        return view('admin.appointments.index', compact(
            'appointments',
            'calendarMonth',
            'nextAppointments',
            'selectedDate',
            'dailyAppointments'
        ));
    }

    public function show(Appointment $appointment): View
    {
        $this->authorizeAdmin();
        $appointment->load(['user', 'service']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function day(Request $request): JsonResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'status' => ['nullable', Rule::enum(AppointmentStatus::class)],
        ]);
        $selectedDate = CarbonImmutable::createFromFormat('Y-m-d', $validated['date'])->startOfDay();
        $appointments = Appointment::with(['user', 'service'])
            ->whereBetween('appointment_at', [$selectedDate, $selectedDate->endOfDay()])
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->oldest('appointment_at')
            ->get()
            ->map(fn (Appointment $appointment): array => [
                'time' => $appointment->appointment_at->format('H:i'),
                'hour' => $appointment->appointment_at->format('H'),
                'minute' => (int) $appointment->appointment_at->format('i'),
                'client' => $appointment->user->full_name,
                'phone' => $appointment->user->phone,
                'service' => $appointment->service->name,
                'duration' => $appointment->service->duration,
                'status' => $appointment->status->value,
                'details_url' => route('admin.appointments.show', $appointment),
            ]);

        return response()->json([
            'date' => $selectedDate->toDateString(),
            'date_label' => $selectedDate->locale('fr')->isoFormat('dddd D MMMM YYYY'),
            'appointments_count' => $appointments->count(),
            'appointments' => $appointments,
        ]);
    }

    public function confirm(Appointment $appointment): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        abort_unless($appointment->status === AppointmentStatus::Pending, 422);
        $appointment->update(['status' => AppointmentStatus::Confirmed]);

        if (request()->wantsJson()) {
            return response()->json([
                'appointment' => $appointment,
                'success' => 'Rendez-vous confirmé.',
            ]);
        }

        return back()->with('success', 'Rendez-vous confirmé.');
    }

    public function complete(Appointment $appointment): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        abort_unless($appointment->status === AppointmentStatus::Confirmed, 422);
        $appointment->update(['status' => AppointmentStatus::Completed]);

        if (request()->wantsJson()) {
            return response()->json([
                'appointment' => $appointment,
                'success' => 'Rendez-vous terminé.',
            ]);
        }

        return back()->with('success', 'Rendez-vous terminé.');
    }

    public function cancel(Appointment $appointment): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        abort_if(in_array($appointment->status, [AppointmentStatus::Completed, AppointmentStatus::Cancelled], true), 422);
        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        if (request()->wantsJson()) {
            return response()->json([
                'appointment' => $appointment,
                'success' => 'Rendez-vous annulé.',
            ]);
        }

        return back()->with('success', 'Rendez-vous annulé.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
