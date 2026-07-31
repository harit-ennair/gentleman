@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">My Orders</h1>@forelse($orders as $order)<a class="rounded bg-white p-4 shadow" href="{{ route('orders.show',$order) }}">{{ $order->order_number }} — {{ $order->total }} DH — {{ $order->status->value }}</a>@empty<p>No orders.</p>@endforelse{{ $orders->links() }}
@endsection
