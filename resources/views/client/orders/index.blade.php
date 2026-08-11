@extends('layouts.test')

@section('title', 'Mes Commandes')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Historique des achats</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Mes Commandes</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Suivez vos commandes de produits, consultez les factures et gérez vos achats.</p>
        </header>

        <div class="rounded-3xl border border-white/10 bg-[#111113] overflow-hidden shadow-2xl">
            @if($orders->isEmpty())
                <div class="p-12 text-center flex flex-col items-center gap-4">
                    <div class="grid size-16 place-items-center rounded-full bg-white/5 text-luxury-secondary">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                        </svg>
                    </div>
                    <h2 class="font-display text-xl font-bold text-white">Aucune commande trouvée</h2>
                    <p class="text-xs text-luxury-secondary max-w-sm">Vous n'avez encore passé aucune commande de produit.</p>
                    <a href="{{ route('products.index') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-md">
                        Acheter des produits &rarr;
                    </a>
                </div>
            @else
                <div class="divide-y divide-white/[0.06]">
                    @foreach($orders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 transition hover:bg-white/[0.03]">
                            <div class="flex items-center gap-4">
                                <div class="grid size-12 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold font-display font-black group-hover:scale-105 transition-transform">
                                    📦
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <div class="flex items-center gap-3">
                                        <h2 class="font-display text-base font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $order->order_number }}</h2>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">
                                            {{ $order->status->label() }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-luxury-secondary">Passée le {{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6">
                                <span class="font-display text-xl font-black text-luxury-gold">
                                    {{ number_format($order->total, 2) }} DH
                                </span>
                                <span class="text-xs font-display font-bold uppercase tracking-wider text-white group-hover:text-luxury-gold flex items-center gap-1">
                                    Détails
                                    <svg class="size-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
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
