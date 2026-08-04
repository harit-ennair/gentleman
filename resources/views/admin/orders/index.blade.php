@extends('layouts.test')

@section('title', 'Admin Orders Directory')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Admin Management</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">All Shop Orders</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Overview, search, and status management for customer product purchases.</p>
        </header>

        <!-- Search & Filter Controls Bar -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-4 sm:p-5 shadow-xl">
            <!-- Search Input -->
            <div class="relative w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-luxury-secondary">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search order # or client name..."
                       class="w-full bg-luxury-bg/60 border border-luxury-border text-white text-xs rounded-2xl pl-10 pr-4 py-3 placeholder-luxury-secondary/50 focus:outline-none focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
            </div>

            <!-- Filters & Reset Controls -->
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                <!-- Order Status Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary hidden sm:inline">Status:</label>
                    <select name="status" onchange="this.form.submit()" class="bg-luxury-bg/60 border border-luxury-border text-white text-xs font-bold rounded-2xl px-3.5 py-3 focus:outline-none focus:border-luxury-gold cursor-pointer transition-all duration-300">
                        <option value="">All Order Statuses</option>
                        @foreach(App\Enums\OrderStatus::cases() as $statusCase)
                            <option value="{{ $statusCase->value }}" {{ request('status') === $statusCase->value ? 'selected' : '' }}>
                                {{ str($statusCase->value)->replace('_', ' ')->title() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary hidden sm:inline">Payment:</label>
                    <select name="payment_status" onchange="this.form.submit()" class="bg-luxury-bg/60 border border-luxury-border text-white text-xs font-bold rounded-2xl px-3.5 py-3 focus:outline-none focus:border-luxury-gold cursor-pointer transition-all duration-300">
                        <option value="">All Payment Statuses</option>
                        @foreach(App\Enums\PaymentStatus::cases() as $paymentCase)
                            <option value="{{ $paymentCase->value }}" {{ request('payment_status') === $paymentCase->value ? 'selected' : '' }}>
                                {{ str($paymentCase->value)->replace('_', ' ')->title() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="inline-flex items-center gap-2 bg-luxury-gold text-black hover:bg-white font-display text-xs font-bold uppercase tracking-wider px-4 py-3 rounded-2xl transition-all duration-300 cursor-pointer shadow-md">
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </button>

                @if(request()->anyFilled(['search', 'status', 'payment_status']))
                    <!-- Clear Filters Link -->
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1 text-xs text-rose-400 hover:text-rose-300 font-bold px-3 py-3 rounded-2xl border border-rose-500/30 bg-rose-500/10 transition-all duration-300">
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>

        <!-- Orders List Container -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] overflow-hidden shadow-2xl">
            @if($orders->isEmpty())
                <div class="p-12 text-center flex flex-col items-center gap-3">
                    <div class="grid size-12 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold text-xl">
                        🔍
                    </div>
                    <h3 class="font-display font-bold text-white text-base">No Orders Found</h3>
                    <p class="text-xs text-luxury-secondary max-w-sm">No shop orders matching your search or filter criteria were found in the system.</p>
                    @if(request()->anyFilled(['search', 'status', 'payment_status']))
                        <a href="{{ route('admin.orders.index') }}" class="mt-2 text-xs font-bold text-luxury-gold hover:underline">
                            Reset Search & Filters
                        </a>
                    @endif
                </div>
            @else
                <div class="divide-y divide-white/[0.06]">
                    @foreach($orders as $order)
                        @php
                            $statusStyle = match($order->status) {
                                App\Enums\OrderStatus::Completed => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                App\Enums\OrderStatus::Processing => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                App\Enums\OrderStatus::Pending => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                App\Enums\OrderStatus::Cancelled, App\Enums\OrderStatus::Refunded => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                default => 'bg-white/5 text-white border-white/10'
                            };

                            $paymentStyle = match($order->payment_status) {
                                App\Enums\PaymentStatus::Paid => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20',
                                App\Enums\PaymentStatus::Pending => 'bg-amber-500/10 text-amber-300 border-amber-500/20',
                                App\Enums\PaymentStatus::Failed => 'bg-rose-500/10 text-rose-300 border-rose-500/20',
                                default => 'bg-white/5 text-zinc-300 border-white/10'
                            };
                        @endphp
                        <a href="{{ route('admin.orders.show', $order) }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 transition hover:bg-white/[0.03]">
                            <div class="flex items-center gap-4">
                                <div class="grid size-12 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold font-display font-black group-hover:scale-105 transition-transform">
                                    📦
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="font-display text-base font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $order->order_number }}</h2>
                                        <span class="rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $statusStyle }}">
                                            {{ str($order->status->value)->replace('_', ' ') }}
                                        </span>
                                        <span class="rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $paymentStyle }}">
                                            {{ $order->payment_status->value }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-luxury-secondary">Client: <strong class="text-white">{{ $order->user->full_name }}</strong> ({{ $order->user->email }}) · {{ $order->created_at->format('M d, Y \a\t H:i') }}</span>
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
