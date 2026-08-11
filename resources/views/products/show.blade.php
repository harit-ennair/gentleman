@extends('layouts.test')

@section('title', $product->name)

@section('content')
    @php
        $imgUrl = null;
        if ($product->image_path) {
            if (str_starts_with($product->image_path, 'http://') || str_starts_with($product->image_path, 'https://')) {
                $imgUrl = $product->image_path;
            } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_path) && !str_contains($product->image_path, 'gY5v50uRlhlhVVojoVnuufR3AYfpkR9b8mQm7WZ1')) {
                $imgUrl = asset('storage/' . $product->image_path);
            }
        }

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

    <div class="mx-auto max-w-4xl flex flex-col gap-8 animate-fade-up">
        <!-- Back Link -->
        <div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la boutique
            </a>
        </div>

        <!-- Product Detail Card Grid -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-10 shadow-2xl shadow-black/40 grid md:grid-cols-12 gap-8 items-center">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <!-- Product Image Column (5 cols) -->
            <div class="md:col-span-5 relative h-72 sm:h-80 w-full overflow-hidden rounded-2xl bg-black/40 flex items-center justify-center p-2 border border-white/10 shadow-inner">
                <img src="{{ $imgUrl }}" alt="{{ $product->name }}" 
                     class="h-full w-full object-cover rounded-xl filter brightness-95 hover:scale-105 transition-transform duration-700">
            </div>

            <!-- Product Details Column (7 cols) -->
            <div class="md:col-span-7 flex flex-col gap-6 relative z-10">
                <div class="flex flex-col gap-2 border-b border-white/10 pb-6">
                    <div class="flex items-center justify-between">
                        <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">
                            {{ $product->category->name ?? 'Essentiel de Soin' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-bold {{ $product->stock_quantity > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            <span class="size-2 rounded-full {{ $product->stock_quantity > 0 ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                            {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' en stock' : 'Rupture de stock' }}
                        </span>
                    </div>

                    <h1 class="font-display text-2xl sm:text-3xl font-black text-white">{{ $product->name }}</h1>

                    <span class="font-display text-3xl font-black text-luxury-gold mt-2">
                        {{ number_format($product->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <h2 class="font-display text-xs font-bold uppercase tracking-wider text-white">Aperçu du produit</h2>
                    <p class="text-xs sm:text-sm text-luxury-secondary leading-relaxed font-light">
                        {{ $product->description ?? 'Formule de qualité professionnelle conçue pour un coiffage haute performance, une tenue longue durée et le soin des cheveux et de la peau.' }}
                    </p>
                </div>

                <div class="border-t border-white/10 pt-6">
                    <form method="POST" action="{{ route('cart.add') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-luxury-secondary uppercase tracking-wider font-bold">Quantité :</span>
                            <!-- Custom Stepper -->
                            <div class="flex items-center rounded-xl border border-white/10 bg-[#161618] p-1 shadow-inner">
                                <button type="button" onclick="const input = this.parentNode.querySelector('input'); if(parseInt(input.value) > 1) input.stepDown();" 
                                        class="size-8 rounded-lg bg-white/5 text-white hover:bg-luxury-gold hover:text-black flex items-center justify-center text-xs font-bold transition-all cursor-pointer">
                                    -
                                </button>
                                <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ max(1, $product->stock_quantity) }}" 
                                       class="w-10 text-center bg-transparent text-xs font-bold text-white focus:outline-none">
                                <button type="button" onclick="const input = this.parentNode.querySelector('input'); input.stepUp();" 
                                        class="size-8 rounded-lg bg-white/5 text-white hover:bg-luxury-gold hover:text-black flex items-center justify-center text-xs font-bold transition-all cursor-pointer">
                                    +
                                </button>
                            </div>
                        </div>

                        <button type="submit" @disabled($product->stock_quantity <= 0) 
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white disabled:opacity-50 cursor-pointer shadow-lg">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                            </svg>
                            Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
