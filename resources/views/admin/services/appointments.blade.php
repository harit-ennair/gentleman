@extends('layouts.test')

@section('title', 'Rendez-vous - ' . $service->name)

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Planning du service</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Rendez-vous : {{ $service->name }}</h1>
            <p class="text-sm text-luxury-secondary max-w-xl">Liste de toutes les réservations clients pour {{ $service->name }}.</p>
        </header>

        <div class="rounded-3xl border border-white/10 bg-[#111113] overflow-hidden shadow-2xl">
            @if($appointments->isEmpty())
                <div class="p-12 text-center flex flex-col items-center gap-4">
                    <p class="text-xs text-luxury-secondary italic">Aucun rendez-vous réservé pour ce service pour le moment.</p>
                </div>
            @else
                <div class="divide-y divide-white/[0.06]">
                    @foreach($appointments as $appointment)
                        <a href="{{ route('admin.appointments.show', $appointment) }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 transition hover:bg-white/[0.03]">
                            <div class="flex items-center gap-4">
                                <div class="grid size-12 shrink-0 place-items-center rounded-2xl bg-luxury-gold/10 text-luxury-gold font-display font-black group-hover:scale-105 transition-transform">
                                    💈
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <div class="flex items-center gap-3">
                                        <h2 class="font-display text-base font-bold text-white group-hover:text-luxury-gold transition-colors">{{ $appointment->user->full_name }}</h2>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">
                                            {{ $appointment->status->label() }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-luxury-secondary">Date : <strong class="text-white">{{ $appointment->appointment_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</strong></span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6">
                                <span class="text-xs font-display font-bold uppercase tracking-wider text-white group-hover:text-luxury-gold flex items-center gap-1">
                                    Voir la réservation &rarr;
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($appointments->hasPages())
                <div class="border-t border-white/10 p-5">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
