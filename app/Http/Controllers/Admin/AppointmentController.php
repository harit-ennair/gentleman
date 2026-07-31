<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();
        $appointments = Appointment::with(['user', 'service'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('appointment_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment): View
    {
        $this->authorizeAdmin();
        $appointment->load(['user', 'service']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function confirm(Appointment $appointment): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_unless($appointment->status === AppointmentStatus::Pending, 422);
        $appointment->update(['status' => AppointmentStatus::Confirmed]);

        return back()->with('success', 'Appointment confirmed.');
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_unless($appointment->status === AppointmentStatus::Confirmed, 422);
        $appointment->update(['status' => AppointmentStatus::Completed]);

        return back()->with('success', 'Appointment completed.');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_if(in_array($appointment->status, [AppointmentStatus::Completed, AppointmentStatus::Cancelled], true), 422);
        $appointment->update(['status' => AppointmentStatus::Cancelled]);

        return back()->with('success', 'Appointment cancelled.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
