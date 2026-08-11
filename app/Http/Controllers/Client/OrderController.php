<?php

namespace App\Http\Controllers\Client;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('orderItems.product')
            ->whereBelongsTo($request->user())
            ->latest()
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['nullable', 'array'],
            'items.*.product_id' => ['required_with:items', Rule::exists(Product::class, 'id')->where('is_active', true)],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        if ($request->filled('items')) {
            $cart = [];
            foreach ($request->input('items') as $item) {
                $cart[$item['product_id']] = (int) $item['quantity'];
            }
        }

        abort_if($cart === [], 422, 'Votre panier est vide.');

        $order = DB::transaction(function () use ($request, $validated, $cart): Order {
            $products = Product::whereIn('id', array_keys($cart))->lockForUpdate()->get()->keyBy('id');
            $total = 0;

            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                abort_if(! $product || ! $product->is_active || $quantity > $product->stock_quantity, 422);
                $total += (float) $product->price * $quantity;
            }

            $order = $request->user()->orders()->create([
                'order_number' => 'ORD-'.now()->format('YmdHis').'-'.str()->upper(str()->random(4)),
                'total' => $total,
                'status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Pending,
                'order_date' => today(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                ]);
                $product->decrement('stock_quantity', $quantity);
            }

            return $order;
        }, attempts: 3);

        $request->session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Commande créée avec succès.',
                'order' => $order->load('orderItems'),
            ], 201);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Commande créée avec succès.');
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOwner($request, $order);
        $order->load('orderItems.product');

        return view('client.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($request, $order);
        abort_unless($order->status === OrderStatus::Pending, 422);

        DB::transaction(function () use ($order): void {
            $order->load('orderItems.product');
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
            $order->update(['status' => OrderStatus::Cancelled]);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Commande annulée.',
                'order' => $order->fresh(),
            ]);
        }

        return back()->with('success', 'Commande annulée.');
    }

    public function invoice(Request $request, Order $order): View
    {
        $this->authorizeOwner($request, $order);
        $order->load(['user', 'orderItems.product']);

        return view('client.orders.invoice', compact('order'));
    }

    private function authorizeOwner(Request $request, Order $order): void
    {
        abort_unless($order->user_id === $request->user()->id, 403);
    }
}
