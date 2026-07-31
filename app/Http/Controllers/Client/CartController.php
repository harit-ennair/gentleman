<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the contents of the session cart.
     */
    public function index(): JsonResponse
    {
        $cart = session('cart', []);
        $items = [];
        $total = 0;

        if (!empty($cart)) {
            $products = Product::whereIn('id', array_keys($cart))->get();

            foreach ($products as $product) {
                $quantity = $cart[$product->id];
                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the session cart.
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = $request->product_id;
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        if (!$product->is_active) {
            return response()->json([
                'message' => 'This product is currently inactive.',
            ], 422);
        }

        $cart = session('cart', []);

        $currentQty = isset($cart[$productId]) ? $cart[$productId] : 0;
        $newQty = $currentQty + $quantity;

        if ($product->stock_quantity < $newQty) {
            return response()->json([
                'message' => "Only {$product->stock_quantity} units of {$product->name} are available in stock.",
            ], 422);
        }

        $cart[$productId] = $newQty;
        session(['cart' => $cart]);

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'cart' => $cart,
        ]);
    }

    /**
     * Update the quantity of a product in the session cart.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        $product = Product::findOrFail($productId);
        $cart = session('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json([
                'message' => 'Product not found in cart.',
            ], 404);
        }

        if ($product->stock_quantity < $quantity) {
            return response()->json([
                'message' => "Only {$product->stock_quantity} units of {$product->name} are available in stock.",
            ], 422);
        }

        $cart[$productId] = $quantity;
        session(['cart' => $cart]);

        return response()->json([
            'message' => 'Cart updated successfully.',
            'cart' => $cart,
        ]);
    }

    /**
     * Remove a product from the session cart.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $productId = $request->product_id;
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return response()->json([
            'message' => 'Product removed from cart successfully.',
            'cart' => $cart,
        ]);
    }

    /**
     * Clear all items from the session cart.
     */
    public function clear(): JsonResponse
    {
        session()->forget('cart');

        return response()->json([
            'message' => 'Cart cleared successfully.',
        ]);
    }
}
