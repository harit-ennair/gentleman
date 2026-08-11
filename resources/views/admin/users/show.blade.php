@extends('layouts.test')

@section('title', 'Profil client - ' . $user->full_name)

@section('content')
    @php
        $firstName = $user->first_name ?? '';
        $lastName = $user->last_name ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        if (empty(trim($initials))) {
            $initials = strtoupper(substr($user->email ?? 'CU', 0, 2));
        }
    @endphp

    <div class="mx-auto max-w-5xl flex flex-col gap-8 animate-fade-up">
        <!-- Navigation & Back Link -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au répertoire clients
            </a>
            <span class="text-xs text-luxury-secondary/70">Inscrit le {{ $user->created_at->locale('fr')->isoFormat('D MMM YYYY') }}</span>
        </div>

        <!-- Header Card -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-2xl shadow-black/40">
            <div class="absolute -right-16 -top-16 size-64 rounded-full bg-luxury-gold/5 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-5">
                    <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-linear-to-br from-luxury-gold/30 to-luxury-gold/10 border border-luxury-gold/40 font-display text-2xl font-black tracking-wider text-luxury-gold shadow-inner">
                        {{ $initials }}
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-3">
                            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Profil client</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-0.5 text-[10px] font-bold {{ $user->is_active ? 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300' : 'border-rose-400/30 bg-rose-400/10 text-rose-300' }}">
                                <span class="size-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                {{ $user->is_active ? 'Compte actif' : 'Désactivé' }}
                            </span>
                        </div>
                        <h1 class="font-display text-2xl font-black text-white sm:text-3xl">{{ $user->full_name }}</h1>
                        <p class="text-xs text-luxury-secondary flex items-center gap-2 font-mono">
                            <span>ID: {{ $user->id }}</span>
                        </p>
                    </div>
                </div>

                <!-- Account Actions / Status Toggle -->
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'désactiver' : 'activer' }} ce compte ?')" 
                            class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 font-display text-xs font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer shadow-md {{ $user->is_active ? 'border border-rose-500/40 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white' : 'border border-emerald-500/40 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500 hover:text-black' }}">
                        @if($user->is_active)
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Désactiver le compte
                        @else
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activer le compte
                        @endif
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-12">
            <!-- Left Column (5 cols): Edit Form -->
            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl">
                    <div class="flex items-center gap-3 border-b border-white/10 pb-4 mb-6">
                        <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display text-base font-bold text-white">Modifier le profil</h2>
                            <p class="text-xs text-luxury-secondary/70">Mettre à jour les coordonnées</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex flex-col gap-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label for="first_name" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Prénom</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                       class="rounded-xl border border-white/10 bg-[#161618] px-3.5 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="last_name" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Nom</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                       class="rounded-xl border border-white/10 bg-[#161618] px-3.5 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="email" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Adresse e-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="rounded-xl border border-white/10 bg-[#161618] px-3.5 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="phone" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Numéro de téléphone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="ex. +212 600-000000"
                                   class="rounded-xl border border-white/10 bg-[#161618] px-3.5 py-2.5 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                        </div>

                        <button type="submit" class="mt-2 rounded-full bg-luxury-gold py-3 px-6 font-display text-xs font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-md text-center">
                            Enregistrer les modifications
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column (7 cols): Activity / Appointments & Orders -->
            <div class="lg:col-span-7 flex flex-col gap-6">
                <!-- Appointments History Card -->
                <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Rendez-vous réservés</h2>
                                <p class="text-xs text-luxury-secondary/70">Historique des réservations du client</p>
                            </div>
                        </div>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-luxury-gold">
                            {{ $user->appointments->count() }} au total
                        </span>
                    </div>

                    @if($user->appointments->isEmpty())
                        <p class="py-6 text-center text-xs text-luxury-secondary/70 italic">Aucun rendez-vous réservé par ce client pour le moment.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($user->appointments->take(5) as $appointment)
                                <a href="{{ route('admin.appointments.show', $appointment) }}" class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs transition hover:border-luxury-gold/40 hover:bg-white/[0.04]">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-white">{{ $appointment->service->name }}</span>
                                        <span class="text-[11px] text-luxury-secondary">{{ $appointment->appointment_at->locale('fr')->isoFormat('D MMM YYYY [à] HH:mm') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full border px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider
                                            {{ $appointment->status->value === 'confirmed' ? 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300' : 
                                               ($appointment->status->value === 'pending' ? 'border-amber-400/30 bg-amber-400/10 text-amber-300' : 
                                               ($appointment->status->value === 'completed' ? 'border-sky-400/30 bg-sky-400/10 text-sky-300' : 'border-rose-400/30 bg-rose-400/10 text-rose-300')) }}">
                                            {{ $appointment->status->label() }}
                                        </span>
                                        <span class="text-luxury-gold">&rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Orders History Card -->
                <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-7 shadow-xl">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Commandes de produits</h2>
                                <p class="text-xs text-luxury-secondary/70">Historique des achats en boutique</p>
                            </div>
                        </div>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-luxury-gold">
                            {{ $user->orders->count() }} au total
                        </span>
                    </div>

                    @if($user->orders->isEmpty())
                        <p class="py-6 text-center text-xs text-luxury-secondary/70 italic">Aucune commande passée par ce client pour le moment.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($user->orders->take(5) as $order)
                                <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] p-3 text-xs transition hover:border-luxury-gold/40 hover:bg-white/[0.04]">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-white">{{ $order->order_number }}</span>
                                        <span class="text-[11px] text-luxury-secondary">{{ $order->created_at->locale('fr')->isoFormat('D MMM YYYY') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-display font-bold text-luxury-gold">{{ number_format($order->total, 2) }} DH</span>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white">
                                            {{ $order->status->label() }}
                                        </span>
                                        <span class="text-luxury-gold">&rarr;</span>
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
