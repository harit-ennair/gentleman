<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * Display a listing of all appointments.
     */
    public function index(): JsonResponse
    {
        $appointments = Appointment::with(['user', 'service'])
            ->latest('appointment_at')
            ->get();

        return response()->json([
            'appointments' => $appointments,
        ]);
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment): JsonResponse
    {
        return response()->json([
            'appointment' => $appointment->load(['user', 'service']),
        ]);
    }

    /**
     * Confirm the specified appointment.
     */
    public function confirm(Appointment $appointment): JsonResponse
    {
        if ($appointment->status !== AppointmentStatus::Pending) {
            return response()->json([
                'message' => 'Only pending appointments can be confirmed.',
            ], 422);
        }

        $appointment->update([
            'status' => AppointmentStatus::Confirmed,
        ]);

        return response()->json([
            'message' => 'Appointment confirmed successfully.',
            'appointment' => $appointment,
        ]);
    }

    /**
     * Complete the specified appointment.
     */
    public function complete(Appointment $appointment): JsonResponse
    {
        if ($appointment->status !== AppointmentStatus::Confirmed) {
            return response()->json([
                'message' => 'Only confirmed appointments can be marked as completed.',
            ], 422);
        }

        $appointment->update([
            'status' => AppointmentStatus::Completed,
        ]);

        return response()->json([
            'message' => 'Appointment marked as completed successfully.',
            'appointment' => $appointment,
        ]);
    }

    /**
     * Cancel the specified appointment.
     */
    public function cancel(Appointment $appointment): JsonResponse
    {
        if (in_array($appointment->status, [AppointmentStatus::Completed, AppointmentStatus::Cancelled])) {
            return response()->json([
                'message' => 'This appointment cannot be cancelled.',
            ], 422);
        }

        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
        ]);

        return response()->json([
            'message' => 'Appointment cancelled successfully.',
            'appointment' => $appointment,
        ]);
    }
}
