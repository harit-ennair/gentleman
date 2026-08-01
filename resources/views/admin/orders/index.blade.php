@extends('layouts.test')

@section('title', 'Admin Orders Directory')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Admin Management</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">All Shop Orders</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Overview and status management for customer product purchases.</p>
        </header>

        <div class="rounded-3xl border border-white/10 bg-[#111113] overflow-hidden shadow-2xl">
            @if($orders->isEmpty())
                <div class="p-12 text-center flex flex-col items-center gap-4">
                    <p class="text-xs text-luxury-secondary italic">No shop orders recorded in system.</p>
                </div>
            @else
                <div class="divide-y divide-white/[0.06]">
                    @foreach($orders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 transition hover:bg-white/[0.03]">
                            <div class="flex items-center gap-4">
                                <div class="grid size-12 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold font-display font-black group-hover:scale-105 transition-transform">
                                    📦
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <div class="flex items-center gap-3">
                                        <h2 class="font-display text-base font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $order->order_number }}</h2>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">
                                            {{ $order->status->value }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-luxury-secondary">Client: <strong class="text-white">{{ $order->user->full_name }}</strong> · {{ $order->created_at->format('M d, Y \a\t H:i') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6">
                                <span class="font-display text-xl font-black text-luxury-gold">
                                    {{ number_format($order->total, 2) }} DH
                                </span>
                                <span class="text-xs font-display font-bold uppercase tracking-wider text-white group-hover:text-luxury-gold flex items-center gap-1">
                                    Manage &rarr;
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($orders->hasPages())
                <div class="border-t border-white/10 p-5">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
