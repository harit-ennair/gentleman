<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GENTLEMAN') | Coiffure & Soins de Luxe pour Hommes</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
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
            <a href="{{ route('products.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Boutique</a>
            <a href="{{ route('categories.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Catégories</a>
            <a href="{{ route('cart.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Panier</a>
            <a href="{{ route('appointments.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Rendez-vous</a>
            <a href="{{ route('orders.index') }}" class="hover:text-luxury-gold transition-colors duration-300">Commandes</a>
            <a href="{{ route('admin.dashboard') }}" class="hover:text-luxury-gold transition-colors duration-300 font-semibold">Administration</a>
        </div>

        <!-- Auth & Theme Actions -->
        <div class="flex items-center gap-4 text-xs font-display uppercase tracking-widest text-luxury-secondary">
            <!-- Theme Toggle Button -->
            <div x-data="{ 
                darkMode: document.documentElement.classList.contains('dark'),
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                }
            }">
                <button @click="toggleTheme()" 
                        type="button"
                        class="flex items-center justify-center w-9 h-9 rounded-full border border-luxury-border bg-luxury-surface text-luxury-primary hover:text-luxury-gold hover:border-luxury-gold transition-all duration-300 shadow-sm cursor-pointer"
                        :title="darkMode ? 'Passer en mode clair' : 'Passer en mode sombre'">
                    <template x-if="darkMode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </template>
                    <template x-if="!darkMode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </template>
                </button>
            </div>

            @auth
                <span class="text-luxury-primary font-light lowercase text-xs">{{ auth()->user()->last_name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-red-950/30 border border-red-900/40 hover:bg-red-900 hover:text-white text-red-400 px-4 py-2 rounded-full text-[10px] font-display font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer">
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-luxury-gold transition-colors duration-300">Connexion</a>
                <a href="{{ route('register') }}" class="bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg px-5 py-2.5 rounded-full text-[10px] font-bold transition-all duration-300 shadow-md">
                    Inscription
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
                <span class="font-semibold">Veuillez corriger les erreurs suivantes :</span>
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
            <span class="text-xs text-luxury-secondary/80 font-light">© 2026 Gentleman Inc. Tous droits réservés.</span>
        </div>

        <!-- Right Links -->
        <div class="flex gap-8 text-xs text-luxury-secondary font-display uppercase tracking-widest">
            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Politique de confidentialité</a>
            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Conditions d'utilisation</a>
            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Carrières</a>
        </div>
    </div>
</footer>

</body>
</html>
