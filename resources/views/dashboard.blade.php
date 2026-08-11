@extends('layouts.test')

@section('title', 'Tableau de bord Client')

@section('content')
    @php
        $user = auth()->user();
        $firstName = $user->first_name ?? '';
        $lastName = $user->last_name ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        if (empty(trim($initials))) {
            $initials = strtoupper(substr($user->email ?? 'CU', 0, 2));
        }

        $upcomingAppointments = $user->appointments()
            ->with('service')
            ->where('appointment_at', '>=', now())
            ->whereNotIn('status', [\App\Enums\AppointmentStatus::Cancelled, \App\Enums\AppointmentStatus::Completed])
            ->oldest('appointment_at')
            ->limit(3)
            ->get();

        $recentOrders = $user->orders()
            ->latest()
            ->limit(3)
            ->get();
    @endphp

    <div class="flex flex-col gap-8 animate-fade-up">
        <!-- Hero Header Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-2xl shadow-black/40">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-5">
                    <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-linear-to-br from-luxury-gold/30 to-luxury-gold/10 border border-luxury-gold/40 font-display text-2xl font-black tracking-wider text-luxury-gold shadow-inner">
                        {{ $initials }}
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-3">
                            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Tableau de bord Client</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-0.5 text-[10px] font-bold text-emerald-300">
                                <span class="size-1.5 rounded-full bg-emerald-400"></span>
                                Membre actif
                            </span>
                        </div>
                        <h1 class="font-display text-2xl font-black text-white sm:text-3xl lg:text-4xl">
                            Bon retour, {{ $user->first_name }}
                        </h1>
                        <p class="text-xs text-luxury-secondary max-w-lg">
                            Gérez vos prochaines séances de soins, consultez vos commandes de produits et personnalisez votre profil.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('appointments.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-5 py-2.5 font-display text-xs font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white cursor-pointer shadow-md">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Prendre rendez-vous
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2.5 font-display text-xs font-bold uppercase tracking-widest text-white transition-all duration-300 hover:border-luxury-gold/50 hover:text-luxury-gold">
                        Modifier le profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Access Stat Cards -->
        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('appointments.index') }}" class="group rounded-2xl border border-white/10 bg-[#111113] p-5 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary group-hover:text-luxury-gold transition-colors">Réservations à venir</span>
                    <div class="grid size-8 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-3xl font-black text-white">{{ $upcomingAppointments->count() }}</p>
                <div class="mt-3 flex items-center justify-between text-xs text-luxury-secondary group-hover:text-white transition-colors">
                    <span>Voir le calendrier</span>
                    <span class="text-luxury-gold group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <a href="{{ route('orders.index') }}" class="group rounded-2xl border border-white/10 bg-[#111113] p-5 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary group-hover:text-luxury-gold transition-colors">Commandes de produits</span>
                    <div class="grid size-8 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-3xl font-black text-white">{{ $recentOrders->count() }}</p>
                <div class="mt-3 flex items-center justify-between text-xs text-luxury-secondary group-hover:text-white transition-colors">
                    <span>Voir les commandes</span>
                    <span class="text-luxury-gold group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}" class="group rounded-2xl border border-white/10 bg-[#111113] p-5 shadow-xl transition-all duration-300 hover:border-luxury-gold/40 hover:bg-white/[0.02]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary group-hover:text-luxury-gold transition-colors">Détails du compte</span>
                    <div class="grid size-8 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-base font-bold text-white truncate">{{ $user->email }}</p>
                <div class="mt-3 flex items-center justify-between text-xs text-luxury-secondary group-hover:text-white transition-colors">
                    <span>Modifier le profil</span>
                    <span class="text-luxury-gold group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>
        </div>

        <!-- Activity Section Grid -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Upcoming Appointments preview -->
            <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Prochaine séance</h2>
                                <p class="text-xs text-luxury-secondary/70">Vos rendez-vous programmés</p>
                            </div>
                        </div>
                        <a href="{{ route('appointments.index') }}" class="text-xs font-display font-bold uppercase tracking-wider text-luxury-gold hover:text-white">Tout afficher</a>
                    </div>

                    @if($upcomingAppointments->isEmpty())
                        <div class="py-8 text-center flex flex-col items-center gap-3">
                            <span class="text-xs text-luxury-secondary/70 italic">Aucun rendez-vous programmé.</span>
                            <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-1 text-xs font-bold text-luxury-gold hover:text-white">
                                + Réserver une séance maintenant
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($upcomingAppointments as $appointment)
                                <a href="{{ route('appointments.show', $appointment) }}" class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs transition hover:border-luxury-gold/40 hover:bg-white/[0.04]">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-10 flex-col items-center justify-center rounded-lg bg-white/[0.06] text-luxury-gold font-display">
                                            <span class="text-[9px] font-bold uppercase">{{ $appointment->appointment_at->locale('fr')->isoFormat('MMM') }}</span>
                                            <span class="text-sm font-black leading-none text-white">{{ $appointment->appointment_at->format('d') }}</span>
                                        </div>
                                        <div class="flex flex-col gap-0.5">
                                            <span class="font-bold text-white">{{ $appointment->service->name }}</span>
                                            <span class="text-[11px] text-luxury-secondary">{{ $appointment->appointment_at->format('H:i') }} · Gentleman Maître Barbier</span>
                                        </div>
                                    </div>
                                    <span class="text-luxury-gold font-bold">&rarr;</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Orders preview -->
            <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Commandes récentes</h2>
                                <p class="text-xs text-luxury-secondary/70">Achats de produits</p>
                            </div>
                        </div>
                        <a href="{{ route('orders.index') }}" class="text-xs font-display font-bold uppercase tracking-wider text-luxury-gold hover:text-white">Tout afficher</a>
                    </div>

                    @if($recentOrders->isEmpty())
                        <div class="py-8 text-center flex flex-col items-center gap-3">
                            <span class="text-xs text-luxury-secondary/70 italic">Aucune commande passée pour le moment.</span>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-luxury-gold hover:text-white">
                                Parcourir les produits de soins &rarr;
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs transition hover:border-luxury-gold/40 hover:bg-white/[0.04]">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-white">{{ $order->order_number }}</span>
                                        <span class="text-[11px] text-luxury-secondary">{{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-display font-bold text-luxury-gold">{{ number_format($order->total, 2) }} DH</span>
                                        <span class="text-luxury-gold font-bold">&rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
