@extends('layouts.test')
@section('title', 'Register')
@section('content')
<div class="relative w-full max-w-xl mx-auto overflow-hidden rounded-3xl border border-luxury-border/60 bg-luxury-surface/50 shadow-2xl backdrop-blur-md p-8 md:p-10 animate-fade-in">
    <!-- Barbershop background pattern watermark -->
    <div class="absolute inset-0 z-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80')] bg-cover bg-center"></div>

    <div class="relative z-10 flex flex-col space-y-6">
        <!-- Logo / Icon -->
        <div class="text-center">
            <span class="inline-block text-luxury-gold text-2xl mb-2">◆</span>
            <h1 class="font-display font-bold text-2xl md:text-3xl text-white tracking-tight uppercase">Create Account</h1>
            <p class="text-luxury-secondary text-xs font-light mt-1">Join the Gentlemen's Club for premium grooming services</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- First Name & Last Name Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2" for="first_name">First Name</label>
                    <input class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40 font-body"
                           id="first_name"
                           type="text"
                           name="first_name"
                           value="{{ old('first_name') }}"
                           placeholder="e.g. John"
                           required
                           autofocus>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2" for="last_name">Last Name</label>
                    <input class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40 font-body"
                           id="last_name"
                           type="text"
                           name="last_name"
                           value="{{ old('last_name') }}"
                           placeholder="e.g. Doe"
                           required>
                </div>
            </div>

            <!-- Email & Phone Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2" for="email">Email Address</label>
                    <input class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40 font-body"
                           id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="e.g. john.doe@example.com"
                           required>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2" for="phone">Phone Number</label>
                    <input class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40 font-body"
                           id="phone"
                           type="text"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="e.g. +1 (555) 123-4567">
                </div>
            </div>

            <!-- Password & Confirm Password Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2" for="password">Password</label>
                    <input class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40 font-body"
                           id="password"
                           type="password"
                           name="password"
                           placeholder="••••••••"
                           required>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2" for="password_confirmation">Confirm Password</label>
                    <input class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40 font-body"
                           id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           placeholder="••••••••"
                           required>
                </div>
            </div>

            <!-- Submit Button -->
            <button class="w-full bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg font-display text-xs font-bold uppercase tracking-widest py-3.5 rounded-xl transition-all duration-300 shadow-lg cursor-pointer">
                Register Account
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center pt-2 border-t border-luxury-border/60">
            <p class="text-xs text-luxury-secondary">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-luxury-gold hover:text-white transition-colors duration-300 font-bold ml-1">
                    Sign In Here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

