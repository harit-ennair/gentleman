<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GENTLEMAN') | Luxury Barbershop & Grooming</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-luxury-bg text-luxury-primary font-body antialiased selection:bg-luxury-gold selection:text-luxury-bg min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="sticky top-0 z-50 bg-luxury-bg/95 backdrop-blur-md py-4 border-b border-luxury-border/60">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="font-display font-black text-2xl tracking-tighter text-luxury-primary flex items-center gap-2 group">
            <span class="text-luxury-gold transform group-hover:rotate-12 transition-transform duration-300">◆</span>
            GENTLEMAN
        </a>

        <!-- Navigation Links -->
        <div class="hidden lg:flex items-center gap-8 font-display text-xs uppercase tracking-widest text-luxury-secondary">
            <a href="{{ route('services.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Services</a>
            <a href="{{ route('products.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Shop</a>
            <a href="{{ route('categories.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Categories</a>
            <a href="{{ route('cart.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Cart</a>
            <a href="{{ route('appointments.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Appointments</a>
            <a href="{{ route('orders.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Orders</a>
            <a href="{{ route('admin.dashboard') }}" class="hover:text-luxury-gold transition-colors duration-300 font-semibold">Admin</a>
        </div>

        <!-- Auth Actions -->
        <div class="flex items-center gap-4 text-xs font-display uppercase tracking-widest text-luxury-secondary">
            @auth
                <span class="text-white font-light lowercase text-xs">{{ auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-red-950/30 border border-red-900/40 hover:bg-red-900 hover:text-white text-red-400 px-4 py-2 rounded-full text-[10px] font-display font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-luxury-gold transition-colors duration-300">Login</a>
                <a href="{{ route('register') }}" class="bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg px-5 py-2.5 rounded-full text-[10px] font-bold transition-all duration-300 shadow-md">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Main Content Area -->
<main class="grow mx-auto w-full max-w-7xl px-6 md:px-12 py-10 flex flex-col justify-start items-stretch">
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-green-950/20 border border-green-800/40 p-4 text-green-400 text-sm flex items-center gap-3">
            <span class="text-green-500">✔</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 rounded-2xl bg-red-950/20 border border-red-800/40 p-4 text-red-400 text-sm">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-red-500 font-bold">⚠</span>
                <span class="font-semibold">Please correct the following:</span>
            </div>
            <ul class="list-disc pl-6 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-luxury-surface border-t border-luxury-border/60 py-12 mt-auto">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
        <!-- Left Info -->
        <div class="flex flex-col md:flex-row items-center gap-6">
            <a href="{{ route('home') }}" class="font-display font-black text-lg tracking-tighter text-white flex items-center gap-1.5">
                <span class="text-luxury-gold">◆</span>
                GENTLEMAN
            </a>
            <span class="hidden md:inline h-4 w-px bg-luxury-border"></span>
            <span class="text-xs text-luxury-secondary/80 font-light">© 2026 Gentleman Inc. All rights reserved.</span>
        </div>

        <!-- Right Links -->
        <div class="flex gap-8 text-xs text-luxury-secondary font-display uppercase tracking-widest">
            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Privacy Policy</a>
            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Terms of Service</a>
            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Careers</a>
        </div>
    </div>
</footer>

</body>
</html>
