@extends('layouts.test')

@section('title', 'Boutique Soins')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <!-- Header -->
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Collection Gentleman</span>
                <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Produits de Soins</h1>
                <p class="text-sm text-luxury-secondary max-w-xl">Argiles coiffantes, pommades, huiles à barbe et essentiels de soins masculins d'exception.</p>
            </div>
        </header>

        <!-- Filter Form Bar -->
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-white/10 bg-[#111113] p-4 shadow-xl">
            <div class="relative grow min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher des produits..." 
                       class="w-full rounded-full border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
            </div>

            <select name="category_id" class="rounded-full border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>

            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Prix min" 
                   class="w-28 rounded-full border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">

            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Prix max" 
                   class="w-28 rounded-full border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">

            <button type="submit" class="rounded-full bg-luxury-gold px-6 py-2.5 font-display text-xs font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-md">
                Filtrer
            </button>

            @if(request('search') || request('category_id') || request('min_price') || request('max_price'))
                <a href="{{ route('products.index') }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2.5 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition hover:text-white">
                    Réinitialiser
                </a>
            @endif
        </form>

        <!-- Product Cards Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-5 shadow-xl transition-all duration-500 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                    <div class="flex flex-col gap-4">
                        <!-- Product Image Container -->
                        <div class="relative h-56 w-full overflow-hidden rounded-2xl bg-black/40 flex items-center justify-center p-2">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="h-full w-full object-cover rounded-xl filter brightness-95 group-hover:scale-105 group-hover:brightness-110 transition-all duration-700">
                            
                            <span class="absolute top-3 left-3 rounded-full border border-luxury-gold/30 bg-luxury-bg/85 backdrop-blur-md px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-luxury-gold shadow-md">
                                {{ $product->category->name ?? 'Soin' }}
                            </span>

                            <span class="absolute top-3 right-3 rounded-full border border-white/10 bg-luxury-bg/85 backdrop-blur-md px-2.5 py-0.5 text-[10px] font-medium {{ $product->stock_quantity > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $product->stock_quantity > 0 ? 'En stock (' . $product->stock_quantity . ')' : 'Rupture de stock' }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <h2 class="font-display text-xl font-bold text-white group-hover:text-luxury-gold transition-colors duration-300">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h2>
                            <p class="text-xs text-luxury-secondary line-clamp-2 leading-relaxed font-light">
                                {{ $product->description ?? 'Formule de qualité professionnelle conçue pour un coiffage haute performance et le soin de la peau.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-4 border-t border-white/10 pt-4">
                        <div class="flex items-center justify-between">
                            <span class="font-display text-2xl font-black text-luxury-gold">
                                {{ number_format($product->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                            </span>
                        </div>

                        <form method="POST" action="{{ route('cart.add') }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <!-- Custom Luxury Quantity Stepper -->
                            <div class="flex items-center rounded-xl border border-white/10 bg-[#161618] p-1 shadow-inner">
                                <button type="button" onclick="const input = this.parentNode.querySelector('input'); if(parseInt(input.value) > 1) input.stepDown();" 
                                        class="size-8 rounded-lg bg-white/5 text-white hover:bg-luxury-gold hover:text-black flex items-center justify-center text-xs font-bold transition-all cursor-pointer">
                                    -
                                </button>
                                <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock_quantity) }}" 
                                       class="w-8 text-center bg-transparent text-xs font-bold text-white focus:outline-none">
                                <button type="button" onclick="const input = this.parentNode.querySelector('input'); input.stepUp();" 
                                        class="size-8 rounded-lg bg-white/5 text-white hover:bg-luxury-gold hover:text-black flex items-center justify-center text-xs font-bold transition-all cursor-pointer">
                                    +
                                </button>
                            </div>

                            <button type="submit" @disabled($product->stock_quantity <= 0) 
                                    class="grow inline-flex items-center justify-center gap-2 rounded-xl bg-luxury-gold px-4 py-2.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white disabled:opacity-50 cursor-pointer shadow-md">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                                </svg>
                                Ajouter au panier
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($products->hasPages())
            <div class="border-t border-white/10 pt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
