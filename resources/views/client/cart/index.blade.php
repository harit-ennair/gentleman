@extends('layouts.test')

@section('title', 'Panier d\'achats')

@section('content')
    <div class="mx-auto max-w-4xl flex flex-col gap-8 animate-fade-up">
        <!-- Header -->
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Votre sélection d'articles</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Panier d'achats</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Vérifiez les produits sélectionnés avant d'effectuer la commande.</p>
        </header>

        @if(empty($items) || count($items) === 0)
            <div class="rounded-3xl border border-white/10 bg-[#111113] p-12 shadow-2xl text-center flex flex-col items-center gap-4">
                <div class="grid size-16 place-items-center rounded-full bg-white/5 text-luxury-secondary">
                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                    </svg>
                </div>
                <div class="flex flex-col gap-1">
                    <h2 class="font-display text-xl font-bold text-white">Votre panier est vide</h2>
                    <p class="text-xs text-luxury-secondary">Vous n'avez encore ajouté aucun produit à votre panier.</p>
                </div>
                <a href="{{ route('products.index') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-md">
                    Explorer les produits &rarr;
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-12">
                <!-- Cart Items List (7 cols) -->
                <div class="lg:col-span-7 flex flex-col gap-4">
                    <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 shadow-xl flex flex-col gap-4">
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <h2 class="font-display text-base font-bold text-white">Articles du panier ({{ count($items) }})</h2>
                            <form method="POST" action="{{ route('cart.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Vider tous les articles de votre panier ?')" class="text-xs font-bold text-rose-400 hover:text-rose-300 uppercase tracking-wider cursor-pointer">
                                    Vider le panier
                                </button>
                            </form>
                        </div>

                        <div class="divide-y divide-white/[0.06]">
                            @foreach($items as $item)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid size-12 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold font-display font-bold">
                                            📦
                                        </div>
                                        <div class="flex flex-col gap-0.5 min-w-0">
                                            <a href="{{ route('products.show', $item['product']) }}" class="font-display text-sm font-bold text-white hover:text-luxury-gold transition-colors truncate">
                                                {{ $item['product']->name }}
                                            </a>
                                            <span class="text-xs text-luxury-gold font-bold">
                                                {{ number_format($item['product']->price, 2) }} DH <span class="text-[10px] text-luxury-secondary font-normal">l'unité</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            
                                            <!-- Custom Stepper -->
                                            <div class="flex items-center rounded-xl border border-white/10 bg-[#161618] p-1 shadow-inner">
                                                <button type="button" onclick="const input = this.parentNode.querySelector('input'); if(parseInt(input.value) > 1) input.stepDown();" 
                                                        class="size-7 rounded-lg bg-white/5 text-white hover:bg-luxury-gold hover:text-black flex items-center justify-center text-xs font-bold transition-all cursor-pointer">
                                                    -
                                                </button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                                       class="w-8 text-center bg-transparent text-xs font-bold text-white focus:outline-none">
                                                <button type="button" onclick="const input = this.parentNode.querySelector('input'); input.stepUp();" 
                                                        class="size-7 rounded-lg bg-white/5 text-white hover:bg-luxury-gold hover:text-black flex items-center justify-center text-xs font-bold transition-all cursor-pointer">
                                                    +
                                                </button>
                                            </div>

                                            <button type="submit" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-[10px] font-bold text-luxury-secondary uppercase tracking-wider hover:text-white hover:border-luxury-gold/50 cursor-pointer">
                                                Mettre à jour
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                            <button type="submit" aria-label="Remove item" class="text-rose-400 hover:text-rose-300 p-1 cursor-pointer">
                                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Checkout Summary Card (5 cols) -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 shadow-xl flex flex-col gap-6">
                        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Récapitulatif de la commande</h2>
                                <p class="text-xs text-luxury-secondary/70">Détails du paiement et de la commande</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-luxury-secondary">Sous-total</span>
                                <span class="font-medium text-white">{{ number_format($total, 2) }} DH</span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-luxury-secondary">Livraison</span>
                                <span class="font-bold text-emerald-400">GRATUIT</span>
                            </div>

                            <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                                <span class="font-display text-sm font-bold text-white">Montant total</span>
                                <span class="font-display text-2xl font-black text-luxury-gold">{{ number_format($total, 2) }} DH</span>
                            </div>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('orders.store') }}" class="flex flex-col gap-4">
                                @csrf
                                <div class="flex flex-col gap-1.5">
                                    <label for="notes" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Instructions de livraison / Remarques</label>
                                    <textarea id="notes" name="notes" rows="2" placeholder="ex. Laisser le colis à la réception..."
                                              class="rounded-xl border border-white/10 bg-[#161618] p-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300"></textarea>
                                </div>

                                <button type="submit" class="w-full rounded-full bg-luxury-gold py-3.5 px-6 font-display text-xs font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-lg text-center">
                                    Passer la commande &rarr;
                                </button>
                            </form>
                        @else
                            <div class="flex flex-col gap-3 rounded-2xl border border-luxury-gold/30 bg-luxury-gold/5 p-4 text-center">
                                <p class="text-xs text-luxury-secondary">Vous devez être connecté pour passer votre commande.</p>
                                <a href="{{ route('login') }}" class="w-full rounded-full bg-luxury-gold py-3 px-6 font-display text-xs font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-lg text-center">
                                    Se connecter pour commander &rarr;
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
