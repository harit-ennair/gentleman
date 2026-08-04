@extends('layouts.test')
@section('title', 'Login')
@section('content')
<div class="relative w-full max-w-md mx-auto overflow-hidden rounded-3xl border border-luxury-border/60 bg-luxury-surface shadow-2xl p-8 md:p-10">
    <!-- Barbershop background pattern watermark -->
    <div class="absolute inset-0 z-0 opacity-5 bg-[url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80')] bg-cover bg-center pointer-events-none"></div>

    <div class="relative z-10 flex flex-col space-y-6">
        <!-- Logo / Icon -->
        <div class="text-center space-y-1">
            <span class="inline-block text-luxury-gold text-2xl mb-1">◆</span>
            <h1 class="font-display font-black text-2xl md:text-3xl text-white tracking-tight uppercase">Welcome Back</h1>
            <p class="text-luxury-secondary text-xs font-medium">Sign in to your luxury Grooming Account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-bold" for="email">Email Address</label>
                <input class="w-full bg-luxury-bg/60 border border-luxury-border rounded-xl px-4 py-3.5 text-xs text-white focus:outline-none focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold transition-all duration-300 placeholder-luxury-secondary/40 font-body"
                       id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="e.g. john.doe@example.com"
                       required
                       autofocus>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-bold" for="password">Password</label>
                <input class="w-full bg-luxury-bg/60 border border-luxury-border rounded-xl px-4 py-3.5 text-xs text-white focus:outline-none focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold transition-all duration-300 placeholder-luxury-secondary/40 font-body"
                       id="password"
                       type="password"
                       name="password"
                       placeholder="••••••••"
                       required>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-luxury-border bg-luxury-bg text-luxury-gold focus:ring-0 focus:ring-offset-0 checked:bg-luxury-gold checked:border-luxury-gold accent-luxury-gold">
                    <span class="text-xs text-luxury-secondary font-medium">Remember me</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-luxury-gold text-black hover:bg-white font-display text-xs font-bold uppercase tracking-widest py-4 rounded-xl transition-all duration-300 shadow-xl cursor-pointer text-center">
                Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="relative flex py-1 items-center">
            <div class="flex-grow border-t border-luxury-border/60"></div>
            <span class="flex-shrink mx-4 text-[9px] uppercase tracking-widest text-luxury-secondary/60 font-display font-bold">Seed Credentials</span>
            <div class="flex-grow border-t border-luxury-border/60"></div>
        </div>

        <!-- Admin Seed Box -->
        <div class="bg-luxury-bg/50 border border-luxury-border/60 rounded-xl p-3.5 text-center shadow-inner">
            <p class="text-[11px] text-luxury-secondary font-medium">
                Admin: <span class="text-luxury-gold font-bold">admin@gentleman.com</span> / <span class="text-white font-bold">password</span>
            </p>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-1">
            <p class="text-xs text-luxury-secondary">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-luxury-gold hover:text-white transition-colors duration-300 font-bold ml-1">
                    Register Here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
