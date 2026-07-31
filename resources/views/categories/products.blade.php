@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $category->name }} products</h1>@foreach($products as $product)<a class="block rounded bg-white p-3 shadow" href="{{ route('products.show',$product) }}">{{ $product->name }} — {{ $product->price }} DH</a>@endforeach{{ $products->links() }}
@endsection
