@extends('layouts.test')

@section('title', $product->name)

@section('content')
    <div class="mx-auto max-w-3xl flex flex-col gap-8 animate-fade-up">
        <!-- Back Link -->
        <div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Shop
            </a>
        </div>

        <!-- Product Detail Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-8 sm:p-10 shadow-2xl shadow-black/40 flex flex-col gap-8">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-white/10 pb-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">{{ $product->category->name ?? 'Grooming Essential' }}</span>
                        <h1 class="font-display text-2xl sm:text-3xl font-black text-white">{{ $product->name }}</h1>
                    </div>
                </div>

                <div class="flex flex-col sm:items-end gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Product Price</span>
                    <span class="font-display text-3xl font-black text-luxury-gold">
                        {{ number_format($product->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-bold {{ $product->stock_quantity > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                        <span class="size-2 rounded-full {{ $product->stock_quantity > 0 ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                        Stock Status: {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' available in stock' : 'Out of stock' }}
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <h2 class="font-display text-sm font-bold uppercase tracking-wider text-white">Product Overview</h2>
                    <p class="text-sm text-luxury-secondary leading-relaxed font-light">
                        {{ $product->description ?? 'Barbershop grade formulation designed for high performance styling, long-lasting hold, and premium skin and hair nourishment.' }}
                    </p>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 relative z-10">
                <form method="POST" action="{{ route('cart.add') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center gap-3">
                        <label for="qty" class="text-xs text-luxury-secondary uppercase tracking-wider font-bold">Quantity:</label>
                        <input id="qty" type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock_quantity) }}" 
                               class="w-20 rounded-xl border border-white/10 bg-[#161618] px-3 py-2.5 text-xs font-bold text-white text-center focus:border-luxury-gold focus:outline-none">
                    </div>

                    <button type="submit" @disabled($product->stock_quantity <= 0) 
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white disabled:opacity-50 cursor-pointer shadow-lg">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                        </svg>
                        Add to Shopping Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
