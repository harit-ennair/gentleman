@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $order->order_number }}</h1><div class="rounded bg-white p-5 shadow"><p>Status: {{ $order->status->value }} / {{ $order->payment_status->value }}</p>@foreach($order->orderItems as $item)<p>{{ $item->product->name }} × {{ $item->quantity }} — {{ $item->unit_price }} DH</p>@endforeach<p class="font-bold">Total {{ $order->total }} DH</p><div class="flex gap-3"><a class="text-blue-600" href="{{ route('orders.invoice',$order) }}">Invoice</a><form method="POST" action="{{ route('orders.cancel',$order) }}">@csrf<button class="text-red-600">Cancel</button></form></div></div>
@endsection
