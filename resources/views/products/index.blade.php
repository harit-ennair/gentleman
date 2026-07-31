@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Products</h1>
<form class="flex flex-wrap gap-2"><input class="rounded border p-2" name="search" value="{{ request('search') }}" placeholder="Search"><select class="rounded border p-2" name="category_id"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select><input class="w-28 rounded border p-2" name="min_price" placeholder="Min"><input class="w-28 rounded border p-2" name="max_price" placeholder="Max"><button class="rounded bg-slate-800 px-4 text-white">Filter</button></form>
<div class="grid gap-4 md:grid-cols-3">@foreach($products as $product)<div class="rounded bg-white p-4 shadow"><a class="font-bold text-blue-600" href="{{ route('products.show',$product) }}">{{ $product->name }}</a><p>{{ $product->price }} DH · Stock {{ $product->stock_quantity }}</p><form method="POST" action="{{ route('cart.add') }}" class="flex gap-2 pt-2">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"><input class="w-20 rounded border p-1" type="number" name="quantity" value="1" min="1"><button class="rounded bg-green-600 px-3 text-white">Add</button></form></div>@endforeach</div>
{{ $products->links() }}
@endsection
