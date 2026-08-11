@extends('layouts.test')

@section('title', 'Carte des Services')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <!-- Header -->
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Savoir-Faire & Soins</span>
                <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Services de Soins</h1>
                <p class="text-sm text-luxury-secondary max-w-xl">Coupes sur mesure, rasages de luxe et soins de barbe d'exception réalisés par nos maîtres barbiers.</p>
            </div>

            @auth
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-6 py-3 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-md">
                    <span class="text-base leading-none">+</span>
                    Prendre rendez-vous
                </a>
            @endauth
        </header>

        <!-- Services Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <div class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <div class="grid size-12 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold group-hover:scale-110 transition-transform duration-300">
                                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 0L4 4m5.121 5.121l7.758 7.758M12 12l-2.879-2.879"/>
                                </svg>
                            </div>
                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-bold text-luxury-gold">
                                {{ $service->duration }} min
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <h2 class="font-display text-xl font-bold text-white group-hover:text-luxury-gold transition-colors duration-300">
                                <a href="{{ route('services.show', $service) }}">{{ $service->name }}</a>
                            </h2>
                            <p class="text-xs text-luxury-secondary line-clamp-3 leading-relaxed font-light">
                                {{ $service->description ?? 'Soin haut de gamme personnalisé selon votre style et vos préférences.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4">
                        <span class="font-display text-2xl font-black text-luxury-gold">
                            {{ number_format($service->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                        </span>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('services.show', $service) }}" class="inline-flex items-center gap-1 text-xs font-display font-bold uppercase tracking-wider text-luxury-secondary transition-colors hover:text-white">
                                Détails
                            </a>
                            <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="inline-flex items-center gap-1 rounded-full bg-luxury-gold px-4 py-2 font-display text-[10px] font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-sm">
                                Réserver &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($services->hasPages())
            <div class="border-t border-white/10 pt-6">
                {{ $services->links() }}
            </div>
        @endif
    </div>
@endsection
