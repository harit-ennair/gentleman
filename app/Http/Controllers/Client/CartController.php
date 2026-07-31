<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::with('category')->whereIn('id', array_keys($cart))->get();
        $items = $products->map(fn (Product $product): array => [
            'product' => $product,
            'quantity' => $cart[$product->id],
            'subtotal' => (float) $product->price * $cart[$product->id],
        ]);
        $total = $items->sum('subtotal');

        return view('client.cart.index', compact('items', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', Rule::exists(Product::class, 'id')->where('is_active', true)],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $product = Product::findOrFail($validated['product_id']);
        $cart = $request->session()->get('cart', []);
        $quantity = ($cart[$product->id] ?? 0) + $validated['quantity'];
        abort_if($quantity > $product->stock_quantity, 422, 'Requested quantity is not available.');
        $cart[$product->id] = $quantity;
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', Rule::exists(Product::class, 'id')],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $product = Product::findOrFail($validated['product_id']);
        abort_if($validated['quantity'] > $product->stock_quantity, 422, 'Requested quantity is not available.');
        $cart = $request->session()->get('cart', []);
        abort_unless(array_key_exists($product->id, $cart), 404);
        $cart[$product->id] = $validated['quantity'];
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate(['product_id' => ['required', 'uuid']]);
        $cart = $request->session()->get('cart', []);
        unset($cart[$validated['product_id']]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return back()->with('success', 'Cart cleared.');
    }
}
