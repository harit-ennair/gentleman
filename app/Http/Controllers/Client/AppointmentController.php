<?php

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the client's appointments.
     */
    public function index(): JsonResponse
    {
        $appointments = Auth::user()
            ->appointments()
            ->with('service')
            ->latest('appointment_at')
            ->get();

        return response()->json([
            'appointments' => $appointments,
        ]);
    }

    /**
     * Store a newly created appointment.
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $appointment = Auth::user()->appointments()->create([
            'service_id' => $validated['service_id'],
            'appointment_at' => Carbon::parse($validated['appointment_at']),
            'status' => AppointmentStatus::Pending,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Appointment booked successfully.',
            'appointment' => $appointment->load('service'),
        ], 201);
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment): JsonResponse
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        return response()->json([
            'appointment' => $appointment->load('service'),
        ]);
    }

    /**
     * Cancel the specified appointment.
     */
    public function cancel(Appointment $appointment): JsonResponse
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

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

    /**
     * Retrieve available booking slots for a given date.
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $date = $request->date;

        // Define operating slots (09:00 to 18:00 with 30-minute intervals)
        $allSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
        ];

        // Fetch booked slots for the day (excluding cancelled ones)
        $bookedSlots = Appointment::whereDate('appointment_at', $date)
            ->where('status', '!=', AppointmentStatus::Cancelled)
            ->get()
            ->map(function ($appointment) {
                return $appointment->appointment_at->format('H:i');
            })
            ->toArray();

        // Calculate available slots
        $availableSlots = array_values(array_diff($allSlots, $bookedSlots));

        return response()->json([
            'date' => $date,
            'available_slots' => $availableSlots,
        ]);
    }
}
