@extends('layouts.test')

@section('title', 'Admin Order Details - ' . $order->order_number)

@section('content')
    <div class="mx-auto max-w-4xl flex flex-col gap-8 animate-fade-up">
        <!-- Back Link -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Orders List
            </a>
            <span class="text-xs text-luxury-secondary/70">Placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</span>
        </div>

        <!-- Header Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-2xl shadow-black/40">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Admin • Order Details</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-0.5 text-[10px] font-bold text-emerald-300">
                            {{ $order->status->value }}
                        </span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-black text-white">{{ $order->order_number }}</h1>
                    <p class="text-xs text-luxury-secondary">Client: <strong class="text-white">{{ $order->user->full_name }}</strong> ({{ $order->user->email }})</p>
                </div>

                <div class="flex flex-col sm:items-end gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Total Amount</span>
                    <span class="font-display text-3xl font-black text-luxury-gold">
                        {{ number_format($order->total, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Items Card -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-xl flex flex-col gap-6">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Order Items</h2>
                    <p class="text-xs text-luxury-secondary/70">Products in this shipment</p>
                </div>
            </div>

            <div class="divide-y divide-white/[0.06]">
                @foreach($order->orderItems as $item)
                    <div class="flex items-center justify-between py-3 text-xs">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-white">{{ $item->product->name }}</span>
                            <span class="text-luxury-secondary">× {{ $item->quantity }}</span>
                        </div>
                        <span class="font-display font-bold text-luxury-gold">
                            {{ number_format($item->quantity * $item->unit_price, 2) }} DH
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Status Update Form -->
            <div class="border-t border-white/10 pt-6">
                <h3 class="font-display text-xs font-bold uppercase tracking-wider text-white mb-4">Update Order Status</h3>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex flex-col sm:flex-row items-center gap-4">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-1 w-full sm:w-auto">
                        <label for="status" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Order Status</label>
                        <select id="status" name="status" class="rounded-xl border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white focus:border-luxury-gold focus:outline-none">
                            @foreach(\App\Enums\OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected($order->status->value === $status->value)>{{ str($status->value)->title() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1 w-full sm:w-auto">
                        <label for="payment_status" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Payment Status</label>
                        <select id="payment_status" name="payment_status" class="rounded-xl border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white focus:border-luxury-gold focus:outline-none">
                            @foreach(\App\Enums\PaymentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected($order->payment_status->value === $status->value)>{{ str($status->value)->title() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="mt-4 sm:mt-[18px] w-full sm:w-auto rounded-full bg-luxury-gold px-6 py-2.5 font-display text-xs font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-md">
                        Update Order Status
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
