<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(): JsonResponse
    {
        $query = Service::query();

        // Check if admin is viewing or not
        $isAdmin = Auth::user() && Auth::user()->can('admin');
        if (!$isAdmin) {
            $query->where('is_active', true);
        }

        $services = $query->latest()->get();

        return response()->json([
            'services' => $services,
        ]);
    }

    /**
     * Store a newly created service.
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image_path'] = $path;
        }

        $data['is_active'] = true;

        $service = Service::create($data);

        return response()->json([
            'message' => 'Service created successfully.',
            'service' => $service,
        ], 201);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): JsonResponse
    {
        return response()->json([
            'service' => $service,
        ]);
    }

    /**
     * Update the specified service.
     */
    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }

            $path = $request->file('image')->store('services', 'public');
            $data['image_path'] = $path;
        }

        $service->update($data);

        return response()->json([
            'message' => 'Service updated successfully.',
            'service' => $service,
        ]);
    }

    /**
     * Toggle the active status of the specified service.
     */
    public function toggleStatus(Service $service): JsonResponse
    {
        $service->update([
            'is_active' => !$service->is_active,
        ]);

        $statusMessage = $service->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'message' => "Service {$statusMessage} successfully.",
            'service' => $service,
        ]);
    }

    /**
     * Display appointments for the specified service.
     */
    public function appointments(Service $service): JsonResponse
    {
        // Enforce admin permission
        if (!Auth::user() || !Auth::user()->can('admin')) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        $appointments = $service->appointments()
            ->with('user')
            ->latest('appointment_at')
            ->get();

        return response()->json([
            'service' => $service,
            'appointments' => $appointments,
        ]);
    }
}
