@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Cart</h1>
@forelse($items as $item)<div class="flex flex-wrap items-center gap-3 rounded bg-white p-3 shadow"><span class="grow">{{ $item['product']->name }} — {{ $item['subtotal'] }} DH</span><form method="POST" action="{{ route('cart.update') }}" class="flex gap-2">@csrf @method('PUT')<input type="hidden" name="product_id" value="{{ $item['product']->id }}"><input class="w-20 border p-1" type="number" name="quantity" value="{{ $item['quantity'] }}"><button>Update</button></form><form method="POST" action="{{ route('cart.remove') }}">@csrf @method('DELETE')<input type="hidden" name="product_id" value="{{ $item['product']->id }}"><button class="text-red-600">Remove</button></form></div>@empty<p>Cart empty.</p>@endforelse
<p class="text-xl font-bold">Total: {{ $total }} DH</p>
<div class="flex gap-2"><form method="POST" action="{{ route('orders.store') }}">@csrf<input class="border p-2" name="notes" placeholder="Order notes"><button class="rounded bg-green-600 p-2 text-white">Checkout</button></form><form method="POST" action="{{ route('cart.clear') }}">@csrf @method('DELETE')<button class="rounded bg-red-600 p-2 text-white">Clear</button></form></div>
@endsection
