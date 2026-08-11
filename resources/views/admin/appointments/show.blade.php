@extends('layouts.test')

@section('title', 'Détails du rendez-vous (Admin)')

@section('content')
    @php
        $status = $appointment->status->value ?? (string) $appointment->status;
        $statusConfig = [
            'pending' => [
                'label' => 'En attente',
                'badge' => 'border-amber-400/30 bg-amber-400/10 text-amber-300 ring-amber-400/20',
                'dot' => 'bg-amber-400',
                'payment' => 'Paiement sur place',
                'payment_badge' => 'border-amber-400/20 bg-amber-400/10 text-amber-300',
            ],
            'confirmed' => [
                'label' => 'Confirmé',
                'badge' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300 ring-emerald-400/20',
                'dot' => 'bg-emerald-400',
                'payment' => 'Confirmé - Paiement sur place',
                'payment_badge' => 'border-emerald-400/20 bg-emerald-400/10 text-emerald-300',
            ],
            'completed' => [
                'label' => 'Terminé',
                'badge' => 'border-sky-400/30 bg-sky-400/10 text-sky-300 ring-sky-400/20',
                'dot' => 'bg-sky-400',
                'payment' => 'Payé intégralement',
                'payment_badge' => 'border-sky-400/20 bg-sky-400/10 text-sky-300',
            ],
            'cancelled' => [
                'label' => 'Annulé',
                'badge' => 'border-rose-400/30 bg-rose-400/10 text-rose-300 ring-rose-400/20',
                'dot' => 'bg-rose-400',
                'payment' => 'Annulé',
                'payment_badge' => 'border-rose-400/20 bg-rose-400/10 text-rose-300',
            ],
            'no_show' => [
                'label' => 'Non présenté',
                'badge' => 'border-zinc-500/30 bg-zinc-500/10 text-zinc-300 ring-zinc-500/20',
                'dot' => 'bg-zinc-400',
                'payment' => 'Non payé',
                'payment_badge' => 'border-zinc-500/20 bg-zinc-500/10 text-zinc-400',
            ],
        ];
        $currentConfig = $statusConfig[$status] ?? $statusConfig['pending'];

        $user = $appointment->user;
        $firstName = $user->first_name ?? '';
        $lastName = $user->last_name ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        if (empty($initials)) {
            $initials = strtoupper(substr($user->email ?? 'CU', 0, 2));
        }

        $startTime = $appointment->appointment_at;
        $duration = $appointment->service->duration ?? 30;
        $endTime = $startTime->copy()->addMinutes($duration);
        $backRoute = route('admin.appointments.index');
    @endphp

    <div class="mx-auto max-w-4xl flex flex-col gap-8 animate-fade-up">
        <!-- Navigation & Top Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au calendrier admin
            </a>

            <div class="flex items-center gap-2">
                <span class="text-xs text-luxury-secondary/70">Réservé le {{ $appointment->created_at->locale('fr')->isoFormat('D MMM YYYY') }}</span>
            </div>
        </div>

        <!-- Main Title Header Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-2xl shadow-black/40">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between relative z-10">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Admin • Détails du rendez-vous</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[11px] font-semibold tracking-wide shadow-sm {{ $currentConfig['badge'] }}">
                            <span class="size-1.5 rounded-full {{ $currentConfig['dot'] }}"></span>
                            {{ $currentConfig['label'] }}
                        </span>
                    </div>

                    <h1 class="font-display text-2xl font-black tracking-tight text-white sm:text-3xl lg:text-4xl">
                        {{ $appointment->service->name }}
                    </h1>

                    <p class="flex items-center gap-2 font-mono text-xs text-luxury-secondary/80">
                        <span>ID: {{ $appointment->id }}</span>
                    </p>
                </div>

                <!-- Price Tag display -->
                <div class="flex sm:flex-col items-baseline sm:items-end justify-between border-t border-white/10 pt-4 sm:border-0 sm:pt-0">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Prix total</span>
                    <span class="font-display text-3xl font-black text-luxury-gold sm:text-4xl">
                        {{ number_format($appointment->service->price, 2) }} <span class="text-xs font-bold text-white/70">DH</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Information Cards Grid -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Customer Information Card -->
            <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl flex flex-col gap-6">
                <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                    <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-display text-base font-bold text-white">Informations du client</h2>
                        <p class="text-xs text-luxury-secondary/70">Coordonnées du client</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="grid size-14 shrink-0 place-items-center rounded-2xl bg-linear-to-br from-luxury-gold/25 to-luxury-gold/5 border border-luxury-gold/30 font-display text-xl font-black tracking-wider text-luxury-gold shadow-inner">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="truncate font-display text-lg font-bold text-white">{{ $user->full_name }}</h3>
                        <p class="truncate text-xs text-luxury-secondary">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Nom complet</span>
                        <span class="font-medium text-white">{{ $user->full_name }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Adresse e-mail</span>
                        <span class="font-medium text-white truncate max-w-[200px]">{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Numéro de téléphone</span>
                        <span class="font-medium text-white">{{ $user->phone ?? 'Non fourni' }}</span>
                    </div>
                </div>
            </div>

            <!-- Appointment Information Card -->
            <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl flex flex-col gap-6">
                <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                    <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-display text-base font-bold text-white">Informations du rendez-vous</h2>
                        <p class="text-xs text-luxury-secondary/70">Planning de réservation & détails du service</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Service</span>
                        <span class="font-bold text-white">{{ $appointment->service->name }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Coiffeur</span>
                        <span class="font-medium text-luxury-gold">Master Coiffeur Gentleman</span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Date</span>
                        <span class="font-medium text-white capitalize">{{ $appointment->appointment_at->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="flex flex-col gap-1 rounded-xl border border-white/[0.06] bg-white/[0.02] p-3">
                            <span class="text-[10px] uppercase tracking-wider text-luxury-secondary">Heure de début</span>
                            <span class="font-display font-bold text-white">{{ $startTime->format('H:i') }}</span>
                        </div>
                        <div class="flex flex-col gap-1 rounded-xl border border-white/[0.06] bg-white/[0.02] p-3">
                            <span class="text-[10px] uppercase tracking-wider text-luxury-secondary">Heure de fin</span>
                            <span class="font-display font-bold text-white">{{ $endTime->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Durée</span>
                        <span class="font-medium text-white">{{ $duration }} minutes</span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs">
                        <span class="text-luxury-secondary">Statut du paiement</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[10px] font-bold {{ $currentConfig['payment_badge'] }}">
                            {{ $currentConfig['payment'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-xl flex flex-col gap-6">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Chronologie du rendez-vous</h2>
                    <p class="text-xs text-luxury-secondary/70">Suivi du statut du rendez-vous</p>
                </div>
            </div>

            <div class="relative px-2 py-4">
                @if ($status === 'cancelled')
                    <!-- Cancelled Timeline Flow -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                        <!-- Step 1: Created -->
                        <div class="relative flex items-start gap-4">
                            <div class="relative z-10 grid size-10 shrink-0 place-items-center rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="font-display text-sm font-bold text-white">Rendez-vous créé</span>
                                <span class="text-xs text-luxury-secondary">{{ $appointment->created_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</span>
                                <p class="text-xs text-luxury-secondary/70 mt-1">Réservation enregistrée dans le système.</p>
                            </div>
                        </div>

                        <!-- Step 2: Cancelled -->
                        <div class="relative flex items-start gap-4">
                            <div class="relative z-10 grid size-10 shrink-0 place-items-center rounded-full bg-rose-500/20 border-2 border-rose-400 text-rose-400">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="font-display text-sm font-bold text-rose-400">Annulé</span>
                                <span class="text-xs text-luxury-secondary">{{ $appointment->updated_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</span>
                                <p class="text-xs text-rose-400/80 mt-1">Ce rendez-vous a été annulé.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Standard Progress Timeline Flow -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                        <!-- Step 1: Created -->
                        <div class="relative flex flex-col items-start gap-2">
                            <div class="flex items-center gap-3">
                                <div class="relative z-10 grid size-10 shrink-0 place-items-center rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="font-display text-sm font-bold text-white">Rendez-vous créé</span>
                            </div>
                            <div class="pl-13 flex flex-col gap-0.5">
                                <span class="text-xs text-luxury-secondary">{{ $appointment->created_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</span>
                                <span class="text-[11px] text-emerald-400 font-medium mt-1">Terminé</span>
                            </div>
                        </div>

                        <!-- Step 2: Confirmed -->
                        @php
                            $isConfirmed = in_array($status, ['confirmed', 'completed'], true);
                            $isCurrentConfirmed = $status === 'pending';
                        @endphp
                        <div class="relative flex flex-col items-start gap-2">
                            <div class="flex items-center gap-3">
                                <div class="relative z-10 grid size-10 shrink-0 place-items-center rounded-full {{ $isConfirmed ? 'bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400' : ($isCurrentConfirmed ? 'bg-amber-500/20 border-2 border-amber-400 text-amber-400' : 'bg-white/5 border-2 border-white/20 text-white/40') }}">
                                    @if ($isConfirmed)
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @elseif($isCurrentConfirmed)
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                <span class="font-display text-sm font-bold {{ $isConfirmed ? 'text-white' : ($isCurrentConfirmed ? 'text-amber-300' : 'text-white/40') }}">
                                    Confirmé
                                </span>
                            </div>
                            <div class="pl-13 flex flex-col gap-0.5">
                                <span class="text-xs text-luxury-secondary">
                                    {{ $isConfirmed ? $appointment->updated_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') : ($isCurrentConfirmed ? 'En attente de confirmation' : 'Programmé') }}
                                </span>
                                <span class="text-[11px] font-medium mt-1 {{ $isConfirmed ? 'text-emerald-400' : ($isCurrentConfirmed ? 'text-amber-400' : 'text-white/40') }}">
                                    {{ $isConfirmed ? 'Terminé' : ($isCurrentConfirmed ? 'En cours' : 'En attente') }}
                                </span>
                            </div>
                        </div>

                        <!-- Step 3: Completed -->
                        @php
                            $isCompleted = $status === 'completed';
                        @endphp
                        <div class="relative flex flex-col items-start gap-2">
                            <div class="flex items-center gap-3">
                                <div class="relative z-10 grid size-10 shrink-0 place-items-center rounded-full {{ $isCompleted ? 'bg-sky-500/20 border-2 border-sky-400 text-sky-400' : 'bg-white/5 border-2 border-white/20 text-white/40' }}">
                                    @if ($isCompleted)
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    @endif
                                </div>
                                <span class="font-display text-sm font-bold {{ $isCompleted ? 'text-sky-300' : 'text-white/40' }}">
                                    Terminé
                                </span>
                            </div>
                            <div class="pl-13 flex flex-col gap-0.5">
                                <span class="text-xs text-luxury-secondary">
                                    {{ $isCompleted ? $appointment->updated_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') : 'Réalisation du service' }}
                                </span>
                                <span class="text-[11px] font-medium mt-1 {{ $isCompleted ? 'text-sky-400' : 'text-white/40' }}">
                                    {{ $isCompleted ? 'Finalisé' : 'À venir' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Notes Card Section -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl flex flex-col gap-4">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Notes</h2>
                    <p class="text-xs text-luxury-secondary/70">Demandes particulières ou informations complémentaires</p>
                </div>
            </div>

            @if (!empty(trim($appointment->notes ?? '')))
                <div class="rounded-2xl border border-white/10 bg-white/[0.025] p-5 text-sm text-luxury-secondary leading-relaxed font-light italic">
                    "{{ $appointment->notes }}"
                </div>
            @else
                <div class="flex items-center gap-3 rounded-2xl border border-white/5 bg-white/[0.015] p-4 text-xs text-luxury-secondary/60 italic">
                    <svg class="size-4 shrink-0 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Aucune note disponible.</span>
                </div>
            @endif
        </div>

        <!-- Actions Section -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-xl flex flex-col gap-5">
            <div class="flex flex-col gap-1">
                <h2 class="font-display text-base font-bold text-white">Actions d'administration</h2>
                <p class="text-xs text-luxury-secondary/70">Gérer le statut ou annuler cette réservation</p>
            </div>

            <div class="flex flex-wrap items-center gap-4 pt-2">
                @if ($status === 'pending')
                    <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-6 py-3 font-display text-xs font-bold uppercase tracking-widest text-luxury-bg transition-all duration-300 hover:bg-white hover:shadow-lg shadow-md cursor-pointer">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirmer le rendez-vous
                        </button>
                    </form>
                @endif

                @if ($status === 'confirmed')
                    <form method="POST" action="{{ route('admin.appointments.complete', $appointment) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-sky-500 px-6 py-3 font-display text-xs font-bold uppercase tracking-widest text-white transition-all duration-300 hover:bg-sky-400 hover:shadow-lg shadow-md cursor-pointer">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Marquer comme terminé
                        </button>
                    </form>
                @endif

                @if (in_array($status, ['pending', 'confirmed'], true))
                    <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')" class="inline-flex items-center justify-center gap-2 rounded-full border border-rose-500/40 bg-rose-500/10 px-6 py-3 font-display text-xs font-bold uppercase tracking-widest text-rose-400 transition-all duration-300 hover:bg-rose-500 hover:text-white cursor-pointer">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annuler le rendez-vous
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
