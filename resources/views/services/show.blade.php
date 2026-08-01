@extends('layouts.test')

@section('title', $service->name)

@section('content')
    <div class="mx-auto max-w-3xl flex flex-col gap-8 animate-fade-up">
        <!-- Back Link -->
        <div>
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Services Menu
            </a>
        </div>

        <!-- Service Detail Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-8 sm:p-10 shadow-2xl shadow-black/40 flex flex-col gap-8">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-b border-white/10 pb-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 0L4 4m5.121 5.121l7.758 7.758M12 12l-2.879-2.879"/>
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Grooming Treatment</span>
                        <h1 class="font-display text-2xl sm:text-3xl font-black text-white">{{ $service->name }}</h1>
                    </div>
                </div>

                <div class="flex flex-col sm:items-end gap-1">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Service Price</span>
                    <span class="font-display text-3xl font-black text-luxury-gold">
                        {{ number_format($service->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-bold text-luxury-gold">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Duration: {{ $service->duration }} minutes
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <h2 class="font-display text-sm font-bold uppercase tracking-wider text-white">Treatment Description</h2>
                    <p class="text-sm text-luxury-secondary leading-relaxed font-light">
                        {{ $service->description ?? 'Experience precision styling tailored specifically to your facial features and personal aesthetic. Executed with premium grooming tools and luxury products.' }}
                    </p>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 relative z-10 flex justify-end">
                @auth
                    <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-lg">
                        Book This Service &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-8 py-3.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white shadow-lg">
                        Login to Book Service
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
