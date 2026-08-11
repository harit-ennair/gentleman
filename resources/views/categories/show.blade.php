@extends('layouts.test')

@section('title', $category->name)

@section('content')
    <div class="mx-auto max-w-3xl flex flex-col gap-8 animate-fade-up">
        <div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux catégories
            </a>
        </div>

        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-8 sm:p-10 shadow-2xl shadow-black/40 flex flex-col gap-6">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex items-center gap-4 border-b border-white/10 pb-6">
                <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold">
                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Collection du Catalogue</span>
                    <h1 class="font-display text-3xl font-black text-white">{{ $category->name }}</h1>
                </div>
            </div>

            <p class="text-sm text-luxury-secondary leading-relaxed font-light">
                {{ $category->description ?? 'Découvrez notre gamme de produits d\'exception dans cette collection.' }}
            </p>

            <div class="border-t border-white/10 pt-6 flex justify-end">
                <a href="{{ route('categories.products', $category) }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-lg">
                    Voir les produits de la catégorie &rarr;
                </a>
            </div>
        </div>
    </div>
@endsection
