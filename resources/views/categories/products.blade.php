@extends('layouts.test')

@section('title', 'Produits ' . $category->name)

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux catégories
            </a>
        </div>

        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Collection de la catégorie</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">{{ $category->name }}</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Navigation dans la catégorie {{ $category->name }}.</p>
        </header>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                @php
                    $imgUrl = ($product->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_path))
                        ? asset('storage/' . $product->image_path)
                        : null;

                    if (!$imgUrl) {
                        $name = strtolower($product->name);
                        if (str_contains($name, 'pomade')) {
                            $imgUrl = 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=600&q=80';
                        } elseif (str_contains($name, 'oil')) {
                            $imgUrl = 'https://images.unsplash.com/photo-1617897903246-719242758050?auto=format&fit=crop&w=600&q=80';
                        } elseif (str_contains($name, 'clay')) {
                            $imgUrl = 'https://images.unsplash.com/photo-1617897902633-82a170b6d214?auto=format&fit=crop&w=600&q=80';
                        } elseif (str_contains($name, 'cream') || str_contains($name, 'shave')) {
                            $imgUrl = 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=600&q=80';
                        } else {
                            $imgUrl = 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=600&q=80';
                        }
                    }
                @endphp

                <a href="{{ route('products.show', $product) }}" class="group flex flex-col justify-between rounded-3xl border border-white/10 bg-[#111113] p-5 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                    <div class="flex flex-col gap-4">
                        <div class="relative h-48 w-full overflow-hidden rounded-2xl bg-black/40 flex items-center justify-center p-4">
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" 
                                 class="h-full w-full object-cover rounded-xl filter brightness-95 group-hover:scale-105 group-hover:brightness-110 transition-all duration-500">
                            
                            <span class="absolute top-3 left-3 rounded-full border border-luxury-gold/30 bg-luxury-bg/85 backdrop-blur-md px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-luxury-gold shadow-md">
                                {{ $category->name }}
                            </span>
                        </div>

                        <div>
                            <h2 class="font-display text-xl font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $product->name }}</h2>
                            <p class="mt-1 text-xs text-luxury-secondary line-clamp-2 leading-relaxed font-light">
                                {{ $product->description ?? 'Formule de qualité professionnelle conçue pour un coiffage haute performance.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4">
                        <span class="font-display text-2xl font-black text-luxury-gold">
                            {{ number_format($product->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                        </span>
                        <span class="text-xs font-display font-bold uppercase text-white group-hover:text-luxury-gold transition-colors">
                            Détails &rarr;
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
