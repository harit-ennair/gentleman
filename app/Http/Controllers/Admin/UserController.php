<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): JsonResponse
    {
        $users = User::latest()->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
        ]);
    }

    /**
     * Toggle the status of the specified user.
     */
    public function toggleStatus(User $user): JsonResponse
    {
        if ($user->id === Auth::id()) {
            return response()->json([
                'message' => 'You cannot deactivate your own account.',
            ], 422);
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $statusMessage = $user->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'message' => "User {$statusMessage} successfully.",
            'user' => $user,
        ]);
    }
}
