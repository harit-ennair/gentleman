@extends('layouts.test')

@section('title', 'Commande ' . $order->order_number)

@section('content')
    <div class="mx-auto max-w-4xl flex flex-col gap-8 animate-fade-up">
        <!-- Back Link -->
        <div class="flex items-center justify-between">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux commandes
            </a>
            <span class="text-xs text-luxury-secondary/70">Passée le {{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</span>
        </div>

        <!-- Header Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-2xl shadow-black/40">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Aperçu de la commande</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-0.5 text-[10px] font-bold text-emerald-300">
                            {{ $order->status->label() }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-0.5 text-[10px] font-bold text-luxury-secondary">
                            Paiement : {{ $order->payment_status->label() }}
                        </span>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-black text-white">{{ $order->order_number }}</h1>
                </div>

                <div class="flex flex-col sm:items-end gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Montant total</span>
                    <span class="font-display text-3xl font-black text-luxury-gold">
                        {{ number_format($order->total, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Items & Details Card -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-xl flex flex-col gap-6">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Produits achetés</h2>
                    <p class="text-xs text-luxury-secondary/70">Articles inclus dans cette commande</p>
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

            <div class="border-t border-white/10 pt-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-6 py-3 font-display text-xs font-bold uppercase tracking-widest text-white transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Voir la facture imprimable
                </a>

                @if(in_array($order->status->value, ['pending', 'processing'], true))
                    <form method="POST" action="{{ route('orders.cancel', $order) }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')" class="inline-flex items-center gap-2 rounded-full border border-rose-500/40 bg-rose-500/10 px-6 py-3 font-display text-xs font-bold uppercase tracking-widest text-rose-400 transition hover:bg-rose-500 hover:text-white cursor-pointer">
                            Annuler la commande
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
