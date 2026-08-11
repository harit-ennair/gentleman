<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $orders = Order::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $this->authorizeAdmin();
        $order->load(['user', 'orderItems.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
            'payment_status' => ['required', Rule::enum(PaymentStatus::class)],
        ]);
        $order->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'order' => $order,
                'success' => 'Statut de la commande mis à jour.',
            ]);
        }

        return back()->with('success', 'Statut de la commande mis à jour.');
    }

    public function cancel(Order $order): RedirectResponse|JsonResponse
    {
        $this->authorizeAdmin();
        abort_if(in_array($order->status, [OrderStatus::Completed, OrderStatus::Cancelled], true), 422);
        $order->update(['status' => OrderStatus::Cancelled]);

        if (request()->wantsJson()) {
            return response()->json([
                'order' => $order,
                'success' => 'Commande annulée.',
            ]);
        }

        return back()->with('success', 'Commande annulée.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
