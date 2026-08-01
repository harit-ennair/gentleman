@extends('layouts.test')

@section('title', 'Customers Directory')

@section('content')
    <div class="flex flex-col gap-8 animate-fade-up">
        <!-- Header -->
        <header class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex flex-col gap-2">
                <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Admin Directory</span>
                <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Customers</h1>
                <p class="text-sm text-luxury-secondary max-w-xl">Manage client profiles, contact information, and account status.</p>
            </div>

            <!-- Search Bar Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-luxury-secondary">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." 
                           class="w-full rounded-full border border-white/10 bg-[#161618] pl-10 pr-4 py-2.5 text-xs text-white placeholder-luxury-secondary/60 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto rounded-full bg-luxury-gold px-5 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer shadow-md">
                        Search
                    </button>

                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary transition hover:text-white">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </header>

        <!-- Stats Bar -->
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-[#111113] p-5 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Total Customers</span>
                    <div class="grid size-8 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 font-display text-3xl font-black text-white">{{ $users->total() }}</p>
            </div>

            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/[0.06] p-5 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Active Accounts</span>
                    <div class="grid size-8 place-items-center rounded-xl bg-emerald-500/10 text-emerald-400">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 font-display text-3xl font-black text-white">{{ $users->getCollection()->where('is_active', true)->count() }}</p>
            </div>

            <div class="rounded-2xl border border-rose-500/20 bg-rose-500/[0.06] p-5 shadow-xl">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-rose-300">Deactivated</span>
                    <div class="grid size-8 place-items-center rounded-xl bg-rose-500/10 text-rose-400">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 font-display text-3xl font-black text-white">{{ $users->getCollection()->where('is_active', false)->count() }}</p>
            </div>
        </div>

        <!-- Customer List Container -->
        <div class="rounded-3xl border border-white/10 bg-[#111113] overflow-hidden shadow-2xl shadow-black/30">
            <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
                <div>
                    <h2 class="font-display text-lg font-bold text-white">Client Accounts</h2>
                    <p class="text-xs text-luxury-secondary/70">Showing registered customers</p>
                </div>
                @if(request('search'))
                    <span class="text-xs text-luxury-gold">Filtered by: "{{ request('search') }}"</span>
                @endif
            </div>

            @if($users->isEmpty())
                <div class="flex flex-col items-center justify-center p-12 text-center">
                    <div class="grid size-14 place-items-center rounded-full bg-white/5 text-luxury-secondary mb-4">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-base font-bold text-white mb-1">No customers found</h3>
                    <p class="text-xs text-luxury-secondary max-w-sm mb-4">No registered clients matched your search filter criteria.</p>
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="rounded-full bg-luxury-gold px-5 py-2 text-xs font-bold text-black uppercase tracking-wider">Reset search</a>
                    @endif
                </div>
            @else
                <div class="divide-y divide-white/[0.06]">
                    @foreach($users as $user)
                        @php
                            $initials = strtoupper(substr($user->first_name ?? 'C', 0, 1) . substr($user->last_name ?? 'U', 0, 1));
                            if (empty(trim($initials))) {
                                $initials = strtoupper(substr($user->email ?? 'CU', 0, 2));
                            }
                        @endphp
                        <a href="{{ route('admin.users.show', $user) }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 sm:px-7 transition-all duration-300 hover:bg-white/[0.03]">
                            <div class="flex items-center gap-4">
                                <div class="grid size-12 shrink-0 place-items-center rounded-2xl bg-linear-to-br from-luxury-gold/25 to-luxury-gold/5 border border-luxury-gold/30 font-display text-base font-black tracking-wider text-luxury-gold shadow-inner group-hover:border-luxury-gold group-hover:scale-105 transition-all duration-300">
                                    {{ $initials }}
                                </div>
                                <div class="flex flex-col gap-0.5 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-display text-base font-bold text-white group-hover:text-luxury-gold transition-colors duration-300">
                                            {{ $user->full_name }}
                                        </h3>
                                        <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-[10px] font-bold {{ $user->is_active ? 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300' : 'border-rose-400/30 bg-rose-400/10 text-rose-300' }}">
                                            <span class="size-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-luxury-secondary truncate">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 text-xs text-luxury-secondary">
                                <div class="flex flex-col sm:items-end">
                                    <span class="text-[10px] uppercase tracking-wider text-luxury-secondary/60">Phone</span>
                                    <span class="font-medium text-white/90">{{ $user->phone ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-luxury-gold font-display text-xs font-bold uppercase tracking-wider group-hover:translate-x-1 transition-transform duration-300">
                                    Manage
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if($users->hasPages())
                <div class="border-t border-white/10 p-5">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
