@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Invoice {{ $order->order_number }}</h1><div class="rounded bg-white p-6 shadow"><p>Client: {{ $order->user->full_name }}</p>@foreach($order->orderItems as $item)<p>{{ $item->product->name }} × {{ $item->quantity }} = {{ $item->quantity * $item->unit_price }} DH</p>@endforeach<p class="font-bold">Total: {{ $order->total }} DH</p><button onclick="window.print()" class="rounded bg-slate-800 p-2 text-white">Print</button></div>
@endsection
