<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Order;
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
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
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

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
            'payment_status' => ['required', Rule::enum(PaymentStatus::class)],
        ]);
        $order->update($validated);

        return back()->with('success', 'Order status updated.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_if(in_array($order->status, [OrderStatus::Completed, OrderStatus::Cancelled], true), 422);
        $order->update(['status' => OrderStatus::Cancelled]);

        return back()->with('success', 'Order cancelled.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
