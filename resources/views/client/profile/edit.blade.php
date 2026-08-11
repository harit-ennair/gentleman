@extends('layouts.test')

@section('title', 'Mon Profil')

@section('content')
    @php
        $user = auth()->user();
        $firstName = $user->first_name ?? '';
        $lastName = $user->last_name ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        if (empty(trim($initials))) {
            $initials = strtoupper(substr($user->email ?? 'CU', 0, 2));
        }
    @endphp

    <div class="mx-auto max-w-4xl flex flex-col gap-8 animate-fade-up">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 font-display text-xs font-bold uppercase tracking-widest text-luxury-secondary transition-colors duration-300 hover:text-luxury-gold">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au tableau de bord
            </a>
            <span class="text-xs text-luxury-secondary/70 capitalize">Membre depuis {{ $user->created_at->locale('fr')->isoFormat('MMM YYYY') }}</span>
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
                            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Profil personnel</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-0.5 text-[10px] font-bold text-emerald-300">
                                <span class="size-1.5 rounded-full bg-emerald-400"></span>
                                Compte actif
                            </span>
                        </div>
                        <h1 class="font-display text-2xl font-black text-white sm:text-3xl">{{ $user->full_name }}</h1>
                        <p class="text-xs text-luxury-secondary flex items-center gap-2 font-mono">
                            <span>E-mail : {{ $user->email }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unified Profile & Password Form Card -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] p-6 sm:p-8 shadow-xl flex flex-col gap-6">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Modifier le profil</h2>
                    <p class="text-xs text-luxury-secondary/70">Mettez à jour vos informations de compte et votre mot de passe</p>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-xs font-semibold text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-6">
                @csrf
                @method('PATCH')

                <!-- Personal Information -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-luxury-gold flex items-center gap-2">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informations personnelles
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="first_name" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Prénom</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                   class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            @error('first_name')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="last_name" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Nom</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                   class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            @error('last_name')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="email" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Adresse e-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            @error('email')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="phone" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Numéro de téléphone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="ex. +212 600 000 000"
                                   class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            @error('phone')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-2"></div>

                <!-- Update Password (Optional) -->
                <div class="flex flex-col gap-4">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-luxury-gold flex items-center gap-2">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Modifier le mot de passe <span class="text-[10px] text-luxury-secondary font-normal lowercase">(optionnel)</span>
                        </h3>
                        <p class="text-[11px] text-luxury-secondary/70 mt-0.5">Laissez vide si vous ne souhaitez pas modifier votre mot de passe</p>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="current_password" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                               class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                        @error('current_password')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="password" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                   class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            @error('password')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Confirmer le mot de passe</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                   class="rounded-xl border border-white/10 bg-[#161618] px-4 py-3 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                            @error('password_confirmation')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button type="submit" class="rounded-full bg-luxury-gold py-3 px-8 font-display text-xs font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-md">
                        Mettre à jour le profil
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
