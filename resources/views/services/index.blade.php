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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <!-- Dynamic service card styled like welcome page -->
                <div class="group bg-luxury-bg border border-luxury-border/60 rounded-2xl overflow-hidden hover:border-luxury-gold/50 transition-all duration-500 flex flex-col h-full shadow-lg">
                    <!-- Image container -->
                    <div class="relative h-64 overflow-hidden bg-black/40">
                        <a href="{{ route('services.show', $service) }}" class="block w-full h-full">
                            <img src="{{ $service->image_url }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale brightness-90 group-hover:grayscale-0 group-hover:brightness-100"
                                alt="{{ $service->name }}">
                        </a>
                        <div class="absolute inset-0 bg-gradient-to-t from-luxury-bg via-transparent to-transparent opacity-60 pointer-events-none"></div>

                        <!-- Category/Badge -->
                        <div class="absolute top-4 right-4 bg-luxury-bg/85 border border-luxury-gold/30 px-3 py-1 rounded-full text-[9px] uppercase tracking-widest text-luxury-gold font-display backdrop-blur-sm pointer-events-none">
                            Service Premium
                        </div>
                    </div>

                    <!-- Card details -->
                    <div class="p-6 md:p-8 flex flex-col flex-grow">
                        <div class="flex items-baseline justify-between mb-4">
                            <h3 class="font-display font-bold text-xl text-white group-hover:text-luxury-gold transition-colors duration-300 uppercase tracking-tight">
                                <a href="{{ route('services.show', $service) }}">{{ $service->name }}</a>
                            </h3>
                            <span class="text-xl font-display font-semibold text-luxury-gold">DH {{ number_format($service->price, 0) }}</span>
                        </div>

                        <p class="text-luxury-secondary text-sm font-light leading-relaxed mb-6 flex-grow">
                            {{ $service->description ?? 'Soin haut de gamme personnalisé selon votre style et vos préférences.' }}
                        </p>

                        <div class="pt-6 border-t border-luxury-border/60 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs text-luxury-secondary font-display uppercase tracking-wider">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-luxury-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $service->duration }} min
                            </span>

                            <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}"
                                class="text-xs uppercase tracking-widest font-display text-white group-hover:text-luxury-gold transition-colors duration-300 font-bold flex items-center gap-1">
                                Réserver <span>→</span>
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

