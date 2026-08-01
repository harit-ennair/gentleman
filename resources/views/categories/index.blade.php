@extends('layouts.test')

@section('title', 'Product Categories')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Catalog Collections</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Categories</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Explore grooming products organized by category.</p>
        </header>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="group flex flex-col justify-between rounded-3xl border border-white/10 bg-[#111113] p-6 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="grid size-12 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold group-hover:scale-110 transition-transform">
                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-luxury-gold">
                            {{ $category->products_count }} products
                        </span>
                    </div>

                    <div>
                        <h2 class="font-display text-xl font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $category->name }}</h2>
                        <p class="mt-1 text-xs text-luxury-secondary line-clamp-2 leading-relaxed font-light">
                            {{ $category->description ?? 'Explore premium products in this collection.' }}
                        </p>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4 text-xs font-bold uppercase tracking-wider text-luxury-gold">
                        <span>Browse Collection</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>

        @if($categories->hasPages())
            <div class="border-t border-white/10 pt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
