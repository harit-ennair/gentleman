@extends('layouts.test')

@section('title', $category->name . ' Products')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Categories
            </a>
        </div>

        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Category Collection</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">{{ $category->name }}</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Browsing products in {{ $category->name }}.</p>
        </header>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" class="group flex flex-col justify-between rounded-3xl border border-white/10 bg-[#111113] p-6 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                    <div class="flex items-center justify-between mb-4">
                        <span class="rounded-full border border-luxury-gold/30 bg-luxury-gold/10 px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider text-luxury-gold">
                            {{ $category->name }}
                        </span>
                        <span class="text-[11px] font-medium text-emerald-400">
                            {{ $product->stock_quantity }} in stock
                        </span>
                    </div>

                    <div>
                        <h2 class="font-display text-xl font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $product->name }}</h2>
                        <p class="mt-1 text-xs text-luxury-secondary line-clamp-2 leading-relaxed font-light">
                            {{ $product->description ?? 'Barbershop grade formulation designed for high performance styling.' }}
                        </p>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4">
                        <span class="font-display text-2xl font-black text-luxury-gold">
                            {{ number_format($product->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                        </span>
                        <span class="text-xs font-display font-bold uppercase text-white group-hover:text-luxury-gold transition-colors">
                            Details &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        @if($products->hasPages())
            <div class="border-t border-white/10 pt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
