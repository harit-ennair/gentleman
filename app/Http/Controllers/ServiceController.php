<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->paginate(12);

        return view('services.index', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $this->validateService($request);
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }
        unset($validated['image']);
        Service::create($validated);

        return back()->with('success', 'Service créé.');
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active || auth()->user()?->role === Role::Admin, 404);

        return view('services.show', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $this->validateService($request);
        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }
        unset($validated['image']);
        $service->update($validated);

        return back()->with('success', 'Service mis à jour.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
        $this->authorizeAdmin();
        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('success', 'Statut du service mis à jour.');
    }

    public function appointments(Service $service): View
    {
        $this->authorizeAdmin();
        $appointments = $service->appointments()->with('user')->latest('appointment_at')->paginate(15);

        return view('admin.services.appointments', compact('service', 'appointments'));
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:5'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
