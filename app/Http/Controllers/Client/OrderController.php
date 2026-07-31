<?php

namespace App\Http\Controllers\Client;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the client's orders.
     */
    public function index(): JsonResponse
    {
        $orders = Auth::user()
            ->orders()
            ->with('orderItems.product')
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated) {
            $total = 0;
            $itemsToCreate = [];

            // Calculate totals and verify/decrement stock
            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}.");
                }

                $product->decrement('stock_quantity', $item['quantity']);

                $unitPrice = $product->price;
                $subtotal = $unitPrice * $item['quantity'];
                $total += $subtotal;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                ];
            }

            // Create the order
            $order = Auth::user()->orders()->create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'total' => $total,
                'status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Pending,
                'order_date' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Save order items
            foreach ($itemsToCreate as $itemData) {
                $order->orderItems()->create([
                    'id' => Str::uuid()->toString(),
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
            }

            return $order;
        });

        // Clear the cart session since the order is placed
        session()->forget('cart');

        return response()->json([
            'message' => 'Order created successfully.',
            'order' => $order->load('orderItems.product'),
        ], 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        return response()->json([
            'order' => $order->load('orderItems.product'),
        ]);
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order): JsonResponse
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.',
            ], 403);
        }

        if ($order->status !== OrderStatus::Pending) {
            return response()->json([
                'message' => 'Only pending orders can be cancelled.',
            ], 422);
        }

        DB::transaction(function () use ($order) {
            // Restore product stock
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

    /**
     * Generate HTML printable invoice.
     */
    public function invoice(Order $order): Response
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'This action is unauthorized.');
        }

        $order->load(['user', 'orderItems.product']);

        $html = view('invoice', ['order' => $order])->render();

        return response($html)
            ->header('Content-Type', 'text/html');
    }
}
