<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $users = User::query()
            ->where('role', Role::Customer)
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $this->authorizeAdmin();
        $user->load(['appointments.service', 'orders.orderItems.product']);

        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
        $user->update($validated);

        return back()->with('success', 'Customer information updated.');
    }

    public function toggleStatus(User $user): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();

        if ($user->is(auth()->user())) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'You cannot deactivate your own account.',
                ], 422);
            }
            abort(422, 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        if (request()->wantsJson()) {
            return response()->json([
                'user' => $user,
                'success' => 'Customer status updated.',
            ]);
        }

        return back()->with('success', 'Customer status updated.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
