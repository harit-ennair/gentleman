@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $product->name }}</h1><div class="rounded bg-white p-5 shadow"><p>{{ $product->description }}</p><p>{{ $product->category->name }} · {{ $product->price }} DH · Stock {{ $product->stock_quantity }}</p></div>
@endsection
