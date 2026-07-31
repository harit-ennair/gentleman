@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">All Orders</h1>@foreach($orders as $order)<a class="rounded bg-white p-4 shadow" href="{{ route('admin.orders.show',$order) }}">{{ $order->order_number }} — {{ $order->user->full_name }} — {{ $order->status->value }}</a>@endforeach{{ $orders->links() }}
@endsection
