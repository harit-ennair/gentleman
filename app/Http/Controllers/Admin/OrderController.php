<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        return response()->json([
            'order' => $order->load(['user', 'orderItems.product']),
        ]);
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): JsonResponse
    {
        $validated = $request->validated();

        $order->update(array_filter([
            'status' => $validated['status'] ?? null,
            'payment_status' => $validated['payment_status'] ?? null,
        ]));

        return response()->json([
            'message' => 'Order status updated successfully.',
            'order' => $order,
        ]);
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order): JsonResponse
    {
        if ($order->status === OrderStatus::Cancelled) {
            return response()->json([
                'message' => 'This order is already cancelled.',
            ], 422);
        }

        DB::transaction(function () use ($order) {
            // Restore product stock if not already cancelled
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update([
                'status' => OrderStatus::Cancelled,
            ]);
        });

        return response()->json([
            'message' => 'Order cancelled successfully.',
            'order' => $order,
        ]);
    }
}
