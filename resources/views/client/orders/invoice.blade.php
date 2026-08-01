@extends('layouts.test')

@section('title', 'Invoice ' . $order->order_number)

@section('content')
    <div class="mx-auto max-w-3xl flex flex-col gap-8 animate-fade-up">
        <div class="flex items-center justify-between">
            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Order
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-full bg-luxury-gold px-5 py-2 font-display text-xs font-bold uppercase tracking-widest text-black hover:bg-white cursor-pointer shadow-md">
                🖨 Print Invoice
            </button>
        </div>

        <div class="rounded-3xl border border-white/10 bg-[#111113] p-8 sm:p-10 shadow-2xl shadow-black/40 flex flex-col gap-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-white/10 pb-6">
                <div class="flex flex-col gap-1">
                    <span class="font-display font-black text-xl tracking-tighter text-white flex items-center gap-2">
                        <span class="text-luxury-gold">◆</span> GENTLEMAN
                    </span>
                    <span class="text-xs text-luxury-secondary">Official Purchase Receipt</span>
                </div>
                <div class="flex flex-col sm:items-end text-xs">
                    <span class="font-display font-bold text-white">Invoice #{{ $order->order_number }}</span>
                    <span class="text-luxury-secondary">{{ $order->created_at->format('F j, Y') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 text-xs border-b border-white/10 pb-6">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] uppercase font-bold text-luxury-gold tracking-widest">Billed To</span>
                    <span class="font-bold text-white text-sm">{{ $order->user->full_name }}</span>
                    <span class="text-luxury-secondary">{{ $order->user->email }}</span>
                    <span class="text-luxury-secondary">{{ $order->user->phone ?? '' }}</span>
                </div>
                <div class="flex flex-col gap-1 sm:items-end">
                    <span class="text-[10px] uppercase font-bold text-luxury-gold tracking-widest">Payment Status</span>
                    <span class="font-bold text-emerald-400 text-sm uppercase">{{ $order->payment_status->value }}</span>
                    <span class="text-luxury-secondary">Order Status: {{ $order->status->value }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="font-display text-xs font-bold uppercase tracking-wider text-white">Items Summary</h2>
                <div class="divide-y divide-white/[0.06] text-xs">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between py-3">
                            <span class="text-white">{{ $item->product->name }} <span class="text-luxury-secondary">× {{ $item->quantity }}</span></span>
                            <span class="font-display font-bold text-luxury-gold">{{ number_format($item->quantity * $item->unit_price, 2) }} DH</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 flex justify-between items-baseline">
                <span class="font-display text-base font-bold text-white">Total Amount Paid</span>
                <span class="font-display text-3xl font-black text-luxury-gold">{{ number_format($order->total, 2) }} DH</span>
            </div>
        </div>
    </div>
@endsection
