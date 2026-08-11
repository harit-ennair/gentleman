<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GENTLEMAN | Coiffure & Salon de Soins de Luxe pour Hommes</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Découvrez des soins d'exception chez Gentleman. Savoir-faire moderne, services haut de gamme et styles classiques conçus pour l'homme contemporain.">
    <meta name="keywords" content="barbier, barbier de luxe, soins, coupe de cheveux, taille de barbe, rasage royal, gentleman">

    <!-- Styles / Scripts -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js (via Laravel mix/vite) -->
</head>

<body class="bg-luxury-bg text-luxury-primary font-body antialiased selection:bg-luxury-gold selection:text-luxury-bg"
    x-data="{
          scrolled: false,
          bookingOpen: false,
          selectedService: '',
          selectedDate: '',
          selectedTime: '',
          bookingSuccess: false,
          activeGalleryImage: null,
          cartCount: 0,
          cartMessage: ''
      }" @scroll.window="scrolled = (window.pageYOffset > 50) ? true : false">

    <!-- Transparent Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 border-b border-transparent"
        :class="scrolled ? 'bg-luxury-bg/95 backdrop-blur-md py-4 border-luxury-border/60' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex items-center justify-between">
            <!-- Logo -->
            <a href="#hero"
                class="font-display font-black text-2xl tracking-tighter text-luxury-primary flex items-center gap-2 group">
                <span
                    class="text-luxury-gold transform group-hover:rotate-12 transition-transform duration-300">◆</span>
                GENTLEMAN
            </a>

            <!-- Navigation Links -->
            <div
                class="hidden lg:flex items-center gap-10 font-display text-xs uppercase tracking-widest text-luxury-secondary">
                <a href="#about" class="hover:text-luxury-gold transition-colors duration-300">Qui sommes-nous</a>
                <a href="#services" class="hover:text-luxury-gold transition-colors duration-300">Services</a>
                <a href="#why-choose" class="hover:text-luxury-gold transition-colors duration-300">Nos piliers</a>
                <a href="#products" class="hover:text-luxury-gold transition-colors duration-300">Boutique</a>
                <a href="#gallery" class="hover:text-luxury-gold transition-colors duration-300">Galerie</a>
                <a href="#testimonials" class="hover:text-luxury-gold transition-colors duration-300">Avis</a>
                <a href="#contact" class="hover:text-luxury-gold transition-colors duration-300">Contact</a>
            </div>

            <!-- CTA Buttons & Theme Toggle -->
            <div class="flex items-center gap-4">
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
                    <button @click="toggleTheme()" type="button"
                        class="flex items-center justify-center w-9 h-9 rounded-full border border-luxury-border bg-luxury-surface/80 text-luxury-primary hover:text-luxury-gold hover:border-luxury-gold transition-all duration-300 shadow-sm cursor-pointer"
                        :title="darkMode ? 'Passer en mode clair' : 'Passer en mode sombre'">
                        <template x-if="darkMode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!darkMode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                        </template>
                    </button>
                </div>

                <!-- Cart Status Indicator -->
                <div class="relative cursor-pointer hover:text-luxury-gold transition-colors duration-300 mr-2"
                    x-show="cartCount > 0" x-transition @click="alert('Redirection vers le panier...')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span
                        class="absolute -top-2 -right-2 bg-luxury-gold text-luxury-bg text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center"
                        x-text="cartCount"></span>
                </div>

                <!-- Login / Account Button -->
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg px-5 py-2.5 rounded-full text-xs font-display font-bold uppercase tracking-wider transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 border border-luxury-gold/60 text-luxury-gold hover:bg-luxury-gold hover:text-luxury-bg px-5 py-2.5 rounded-full text-xs font-display font-bold uppercase tracking-wider transition-all duration-300 shadow-sm backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Connexion
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    <!-- Cart Add Toast Notification -->
    <div class="fixed bottom-6 right-6 z-50 bg-luxury-surface border border-luxury-gold text-luxury-primary px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-500 transform translate-y-0"
        x-show="cartMessage !== ''" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4">
        <span class="text-luxury-gold">✔</span>
        <span class="text-sm font-semibold" x-text="cartMessage"></span>
    </div>

    <!-- Hero Section -->
    <section id="hero" class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden">
        <!-- Cinematic Background Video -->
        <div id="hero-video-bg" class="absolute inset-0 z-0">
            <video class="w-full h-full object-cover" autoplay loop muted playsinline preload="auto"
                poster="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=1950&q=80">
                <source src="/videos/hero-bg.mp4" type="video/mp4">
            </video>
            <!-- Dark overlay for text readability (50%) -->
            <div class="absolute inset-0" style="background-color: rgba(0,0,0,0.5)"></div>
            <!-- Gradient overlay for depth -->
            <div class="absolute inset-0"
                style="background: linear-gradient(to top, #0B0B0B, transparent 60%, rgba(0,0,0,0.3))"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 text-center flex flex-col items-center mt-16">
            <!-- Editorial Subtitle -->
            <div
                class="inline-flex items-center gap-3 text-luxury-gold uppercase tracking-[0.3em] text-xs font-display mb-6 animate-fade-in">
                <span class="h-px w-8 bg-luxury-gold"></span>
                Depuis 2026 • Coiffure & Salon de Soins
                <span class="h-px w-8 bg-luxury-gold"></span>
            </div>

            <!-- Massive Header -->
            <h1
                class="font-display font-black text-6xl md:text-8xl lg:text-[110px] leading-[0.9] uppercase tracking-tighter text-white max-w-5xl mb-8 animate-fade-up">
                L'ART DU<br>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-luxury-primary via-luxury-gold to-luxury-primary">STYLE INTEMPOREL</span>
            </h1>

            <!-- Description -->
            <p class="text-luxury-secondary text-lg md:text-xl font-light max-w-2xl leading-relaxed mb-10 animate-fade-up"
                style="animation-delay: 150ms">
                Des expériences de soins haut de gamme pour l'homme moderne. Nous allions héritage classique et style contemporain pour forger un caractère inoubliable.
            </p>

            <!-- Dual CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-up"
                style="animation-delay: 300ms">
                <a href="#services"
                    class="border border-luxury-border hover:border-luxury-gold px-8 py-4 rounded-full text-xs font-display font-bold uppercase tracking-widest text-luxury-primary transition-all duration-500 w-full sm:w-auto text-center backdrop-blur-sm">
                    Découvrir les services
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <a href="#about" class="absolute bottom-10 z-10 flex flex-col items-center gap-2 group animate-bounce">
            <span
                class="text-[9px] uppercase tracking-[0.2em] font-display text-luxury-secondary group-hover:text-luxury-gold transition-colors duration-300">Défiler vers le bas</span>
            <svg class="h-4 w-4 text-luxury-secondary group-hover:text-luxury-gold transition-colors duration-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </a>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 md:py-36 bg-luxury-bg border-b border-luxury-border/30 relative">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <!-- Asymmetrical Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left text: 5 Columns -->
                <div class="lg:col-span-6 flex flex-col justify-center">
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">QUI SOMMES-NOUS</span>
                    <h2
                        class="font-display font-bold text-4xl md:text-5xl lg:text-6xl leading-tight uppercase tracking-tight text-white mb-8">
                        L'ART DE LA<br>
                        MASCULINITÉ MODERNE
                    </h2>
                    <p class="text-luxury-secondary text-base md:text-lg font-light leading-relaxed mb-6">
                        Gentleman est plus qu'un salon de coiffure ; c'est un sanctuaire d'exception pour l'homme moderne. Fondé sur la précision, des soins sur-mesure et un savoir-faire inégalé, nos maîtres barbiers créent une signature personnalisée pour chaque client.
                    </p>
                    <p class="text-luxury-secondary/80 text-sm md:text-base font-light leading-relaxed mb-8">
                        Chaque prestation débute par une analyse personnalisée. Nous sélectionnons des soins biologiques haut de gamme et sculptons des contours qui mettent en valeur votre personnalité.
                    </p>

                    <!-- Quote -->
                    <div
                        class="border-l-2 border-luxury-gold pl-6 py-2 mb-8 bg-luxury-surface/30 pr-4 rounded-r-xl border-t border-b border-r border-luxury-border/10">
                        <p class="text-sm md:text-base font-serif italic text-luxury-primary leading-relaxed">
                            "Le style est la signature du respect de soi. Notre objectif est de rendre cette signature nette, élégante et incontournable."
                        </p>
                        <span class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display mt-2">—
                            Alexander Mercer, Maître Barbier Principal</span>
                    </div>
                </div>

                <!-- Right Images: 6 Columns -->
                <div class="lg:col-span-6 grid grid-cols-12 gap-6 relative">
                    <!-- Accent decorative background frame -->
                    <div
                        class="absolute -inset-4 border border-luxury-gold/10 rounded-3xl pointer-events-none -z-10 transform rotate-1">
                    </div>

                    <!-- Left larger image -->
                    <div
                        class="col-span-8 overflow-hidden rounded-2xl border border-luxury-border shadow-2xl relative group">
                        <img src="https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=800&q=80"
                            class="w-full h-[400px] object-cover filter grayscale hover:grayscale-0 transition-all duration-700 ease-in-out transform hover:scale-105"
                            alt="Coiffure en cours">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                            <span
                                class="text-xs uppercase tracking-widest text-luxury-gold font-display font-semibold">Savoir-faire de précision</span>
                        </div>
                    </div>

                    <!-- Right offset smaller image -->
                    <div
                        class="col-span-4 self-end overflow-hidden rounded-xl border border-luxury-border shadow-2xl relative group mb-8">
                        <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=600&q=80"
                            class="w-full h-[220px] object-cover filter grayscale hover:grayscale-0 transition-all duration-700 ease-in-out transform hover:scale-105"
                            alt="Détails de l'espace haut de gamme">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span
                                class="text-[9px] uppercase tracking-widest text-luxury-gold font-display font-semibold">Espace d'exception</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 md:py-36 bg-luxury-surface border-b border-luxury-border/30">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span
                        class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">EXPÉRIENCES</span>
                    <h2
                        class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tight text-white">
                        NOS EXPÉRIENCES
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                        Une sélection minutieuse de soins conçus pour sculpter votre style, restaurer votre énergie et sublimer votre allure.
                    </p>
                </div>
            </div>

            <!-- Service Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($services as $service)
                    <!-- Dynamic service card -->
                    <div
                        class="group bg-luxury-bg border border-luxury-border/60 rounded-2xl overflow-hidden hover:border-luxury-gold/50 transition-all duration-500 flex flex-col h-full shadow-lg">
                        <!-- Image container -->
                        <div class="relative h-64 overflow-hidden bg-black/40">
                            <!-- Image from database or Fallbacks based on service name -->
                            @php
                                $imgUrl = ($service->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->image_path))
                                    ? asset('storage/' . $service->image_path)
                                    : null;
                                if (!$imgUrl) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=600&q=80';
                                    if (str_contains(strtolower($service->name), 'haircut')) {
                                        $imgUrl = 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($service->name), 'beard')) {
                                        $imgUrl = 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($service->name), 'shave')) {
                                        $imgUrl = 'https://images.unsplash.com/photo-1517832606589-7a598bb03b15?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($service->name), 'color')) {
                                        $imgUrl = 'https://images.unsplash.com/photo-1605497746444-17dbd873c988?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($service->name), 'beard') && str_contains(strtolower($service->name), 'hair')) {
                                        $imgUrl = 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80';
                                    }
                                }
                            @endphp
                            <img src="{{ $imgUrl }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale brightness-90 group-hover:grayscale-0 group-hover:brightness-100"
                                alt="{{ $service->name }}">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-luxury-bg via-transparent to-transparent opacity-60">
                            </div>

                            <!-- Category/Badge -->
                            <div
                                class="absolute top-4 right-4 bg-luxury-bg/85 border border-luxury-gold/30 px-3 py-1 rounded-full text-[9px] uppercase tracking-widest text-luxury-gold font-display backdrop-blur-sm">
                                Service Premium
                            </div>
                        </div>

                        <!-- Card details -->
                        <div class="p-6 md:p-8 flex flex-col flex-grow">
                            <div class="flex items-baseline justify-between mb-4">
                                <h3
                                    class="font-display font-bold text-xl text-white group-hover:text-luxury-gold transition-colors duration-300 uppercase tracking-tight">
                                    {{ $service->name }}
                                </h3>
                                <span class="text-xl font-display font-semibold text-luxury-gold">DH
                                    {{ number_format($service->price, 0) }}</span>
                            </div>

                            <p class="text-luxury-secondary text-sm font-light leading-relaxed mb-6 flex-grow">
                                {{ $service->description }}
                            </p>

                            <div class="pt-6 border-t border-luxury-border/60 flex items-center justify-between">
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs text-luxury-secondary font-display uppercase tracking-wider">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-luxury-gold" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $service->duration }} min
                                </span>

                                <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}"
                                    class="text-xs uppercase tracking-widest font-display text-white group-hover:text-luxury-gold transition-colors duration-300 font-bold flex items-center gap-1">
                                    Réserver <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Gentleman -->
    <section id="why-choose" class="py-24 md:py-36 bg-luxury-bg border-b border-luxury-border/30 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                <!-- Left sticky column: 4 Columns -->
                <div class="lg:col-span-5 lg:sticky lg:top-32">
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">NOTRE MANIFESTE</span>
                    <h2
                        class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tighter leading-tight text-white mb-6">
                        LE STANDARD<br>
                        GENTLEMAN
                    </h2>
                    <p class="text-luxury-secondary text-base font-light leading-relaxed mb-8">
                        Nous exigeons l'excellence pour chacun de nos services et de nos soins clients. Chaque détail est pensé pour créer un environnement parfait.
                    </p>

                    <!-- Decorative graphics -->
                    <div
                        class="hidden lg:block relative h-48 w-full border border-luxury-border rounded-2xl overflow-hidden bg-luxury-surface/20">
                        <div class="absolute inset-0 bg-cover bg-center filter grayscale opacity-40 hover:opacity-85 transition-opacity duration-700"
                            style="background-image: url('https://images.unsplash.com/photo-1512690196222-7c7c72491214?auto=format&fit=crop&w=600&q=80')">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-luxury-bg to-transparent"></div>
                        <div
                            class="absolute bottom-4 left-4 font-display text-[10px] tracking-widest text-luxury-gold uppercase font-bold">
                            Lames de précision uniquement</div>
                    </div>
                </div>

                <!-- Right reasons list: 7 Columns -->
                <div class="lg:col-span-7 divide-y divide-luxury-border/60">
                    <!-- Item 1 -->
                    <div class="py-8 first:pt-0">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">01</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">
                                    Barbiers professionnels</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Nos maîtres barbiers sont sélectionnés pour leur expertise technique et leur maîtrise des soins traditionnels.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">02</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">
                                    Produits de luxe</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Nous élaborons exclusivement des huiles biologiques, toniques et soins capillaires aux ingrédients naturels d'exception.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">03</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">
                                    Expérience haut de gamme</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Dégustez un café d'exception ou un whisky ambré dans nos fauteuils en cuir chauffants pendant vos soins.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 4 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">04</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">
                                    Équipement moderne</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Outils de haute performance, bacs de lavage japonais et vapozones avancés pour un confort optimal.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 5 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">05</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">
                                    Hygiène irréprochable</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Nous appliquons des protocoles d'hygiène stricts. Chaque rasoir, peigne et fauteuil est stérilisé entre chaque client.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 6 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">06</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">
                                    Service personnalisé</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Votre historique de soins est archivé pour adapter chaque visite à vos préférences.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="products" class="py-24 md:py-36 bg-luxury-surface border-b border-luxury-border/30">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">BOUTIQUE EXCLUSIVE</span>
                    <h2
                        class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tight text-white">
                        LA COLLECTION
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                        Emportez l'expérience Gentleman chez vous. Nos formules de coiffage et de soin de la barbe sont préparées avec soin.
                    </p>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($latestProducts as $product)
                    <!-- Dynamic Product Card -->
                    <div
                        class="group bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/50 rounded-2xl overflow-hidden transition-all duration-500 flex flex-col h-full shadow-lg">
                        <!-- Image Container -->
                        <div class="relative h-64 overflow-hidden bg-black/30 flex items-center justify-center p-6">
                            <!-- Specific Fallback Images -->
                            @php
                                $imgProd = ($product->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_path))
                                    ? asset('storage/' . $product->image_path)
                                    : null;
                                if (!$imgProd) {
                                    $imgProd = 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=600&q=80';
                                    if (str_contains(strtolower($product->name), 'pomade')) {
                                        $imgProd = 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($product->name), 'oil')) {
                                        $imgProd = 'https://images.unsplash.com/photo-1617897903246-719242758050?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($product->name), 'clay')) {
                                        $imgProd = 'https://images.unsplash.com/photo-1617897902633-82a170b6d214?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains(strtolower($product->name), 'cream')) {
                                        $imgProd = 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=600&q=80';
                                    }
                                }
                            @endphp
                            <img src="{{ $imgProd }}"
                                class="max-h-56 max-w-full object-contain filter grayscale brightness-95 group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
                                alt="{{ $product->name }}">

                            <!-- Quick add on hover overlay -->
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                <button
                                    @click="cartCount++; cartMessage = 'Ajouté ' + '{{ $product->name }}' + ' au panier'; setTimeout(() => cartMessage = '', 3000)"
                                    class="bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg px-6 py-3 rounded-full text-xs font-display font-bold uppercase tracking-wider transition-all duration-300">
                                    Ajout rapide • {{ number_format($product->price, 0) }} DH
                                </button>
                            </div>

                            <span
                                class="absolute top-4 left-4 bg-luxury-surface/80 border border-luxury-border px-2.5 py-0.5 rounded-full text-[9px] uppercase tracking-widest text-luxury-secondary font-display">
                                {{ $product->category->name ?? 'Collection' }}
                            </span>
                        </div>

                        <!-- Content Details -->
                        <div class="p-6 flex flex-col flex-grow border-t border-luxury-border/30">
                            <h3
                                class="font-display font-bold text-lg text-white mb-2 group-hover:text-luxury-gold transition-colors duration-300 uppercase tracking-tight">
                                {{ $product->name }}
                            </h3>
                            <p class="text-luxury-secondary/85 text-xs font-light leading-relaxed mb-6 flex-grow">
                                {{ $product->description }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-luxury-border/30">
                                <span
                                    class="text-base font-display font-bold text-luxury-gold">{{ number_format($product->price, 0) }}
                                    DH</span>
                                <button
                                    @click="cartCount++; cartMessage = 'Ajouté ' + '{{ $product->name }}' + ' au panier'; setTimeout(() => cartMessage = '', 3000)"
                                    class="text-xs uppercase tracking-widest font-display text-white hover:text-luxury-gold transition-colors duration-300 font-bold flex items-center gap-1">
                                    Ajouter au panier <span>+</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-24 md:py-36 bg-luxury-bg border-b border-luxury-border/30">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">GALLERY</span>
                    <h2
                        class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tight text-white">
                        THE GALLERY
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                        Un aperçu visuel de notre savoir-faire et de notre style. Découvrez les réalisations créées dans nos studios.
                    </p>
                </div>
            </div>

            <!-- Masonry Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $galleryImages = [
                        ['src' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=800&q=80', 'title' => 'Dégradé & Taille de barbe'],
                        ['src' => 'https://images.unsplash.com/photo-1622287198514-5d10f64aaab7?auto=format&fit=crop&w=800&q=80', 'title' => 'Soin Serviette Chaude'],
                        ['src' => 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=800&q=80', 'title' => 'Coffret de Soins de Luxe'],
                        ['src' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80', 'title' => 'Mousse Chaude Traditionnelle'],
                        ['src' => 'https://images.unsplash.com/photo-1512690196222-7c7c72491214?auto=format&fit=crop&w=800&q=80', 'title' => 'Rasage au Coupe-Chou'],
                        ['src' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80', 'title' => 'Le Salon Gentleman']
                    ];
                @endphp

                @foreach ($galleryImages as $index => $item)
                    <div class="group relative overflow-hidden rounded-2xl border border-luxury-border/60 aspect-square cursor-pointer shadow-lg"
                        @click="activeGalleryImage = '{{ $item['src'] }}'">
                        <img src="{{ $item['src'] }}"
                            class="w-full h-full object-cover filter grayscale hover:grayscale-0 transform hover:scale-105 transition-all duration-700 ease-in-out"
                            alt="{{ $item['title'] }}">
                        <div
                            class="absolute inset-0 bg-black/75 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6 border border-luxury-gold/20 rounded-2xl">
                            <span
                                class="text-luxury-gold font-display text-[10px] tracking-[0.2em] uppercase mb-1">Vue interactive</span>
                            <h4 class="font-display font-bold text-lg text-white uppercase tracking-tight">
                                {{ $item['title'] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Fullscreen Gallery Modal Overlay -->
        <div class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-6"
            x-show="activeGalleryImage !== null" x-transition @click="activeGalleryImage = null" style="display: none;">
            <button class="absolute top-6 right-6 text-white hover:text-luxury-gold transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img :src="activeGalleryImage"
                class="max-w-full max-h-[90vh] object-contain rounded-2xl border border-luxury-border"
                alt="Vue agrandie">
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-24 md:py-36 bg-luxury-surface border-b border-luxury-border/30">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <!-- Header -->
            <div class="text-center max-w-2xl mx-auto mb-20">
                <span
                    class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3 flex items-center justify-center gap-2">
                    <span class="h-px w-6 bg-luxury-gold"></span>
                    TÉMOIGNAGES
                    <span class="h-px w-6 bg-luxury-gold"></span>
                </span>
                <h2 class="font-display font-bold text-4xl md:text-5xl uppercase tracking-tighter text-white">
                    CE QU'ILS DISENT
                </h2>
            </div>

            <!-- Testimonial Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div
                    class="bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/30 rounded-2xl p-8 transition-all duration-500 shadow-md relative">
                    <div class="text-luxury-gold flex gap-1 mb-6 text-sm">★★★★★</div>
                    <p class="text-luxury-secondary text-sm md:text-base font-light italic leading-relaxed mb-8">
                        "L'analyse de style a totalement transformé mon allure. Ils ont étudié la forme de mon visage et m'ont conseillé une coupe classique parfaite. L'ambiance est luxueuse et relaxante."
                    </p>
                    <div class="flex items-center gap-4 pt-6 border-t border-luxury-border/40">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80"
                            class="h-12 w-12 rounded-full object-cover filter grayscale" alt="David Sterling">
                        <div>
                            <h4 class="font-display font-bold text-sm uppercase text-white tracking-tight">David
                                Sterling</h4>
                            <span
                                class="text-[10px] uppercase tracking-widest text-luxury-secondary">Entrepreneur</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/30 rounded-2xl p-8 transition-all duration-500 shadow-md relative">
                    <div class="text-luxury-gold flex gap-1 mb-6 text-sm">★★★★★</div>
                    <p class="text-luxury-secondary text-sm md:text-base font-light italic leading-relaxed mb-8">
                        "Le Rasage Royal est une expérience légendaire. Huiles essentielles avant-rasage, mousse onctueuse, serviettes chaudes et finition au coupe-chou. Un vrai moment de luxe."
                    </p>
                    <div class="flex items-center gap-4 pt-6 border-t border-luxury-border/40">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&h=150&q=80"
                            class="h-12 w-12 rounded-full object-cover filter grayscale" alt="James Harrison">
                        <div>
                            <h4 class="font-display font-bold text-sm uppercase text-white tracking-tight">James
                                Harrison</h4>
                            <span class="text-[10px] uppercase tracking-widest text-luxury-secondary">Directeur Créatif</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div
                    class="bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/30 rounded-2xl p-8 transition-all duration-500 shadow-md relative">
                    <div class="text-luxury-gold flex gap-1 mb-6 text-sm">★★★★★</div>
                    <p class="text-luxury-secondary text-sm md:text-base font-light italic leading-relaxed mb-8">
                        "Une précision inégalée. Mon barbier a étudié la pousse de mes cheveux pour un dégradé à blanc impeccable. À recommander à tout homme en quête de perfection."
                    </p>
                    <div class="flex items-center gap-4 pt-6 border-t border-luxury-border/40">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&h=150&q=80"
                            class="h-12 w-12 rounded-full object-cover filter grayscale" alt="Charles Kingsley">
                        <div>
                            <h4 class="font-display font-bold text-sm uppercase text-white tracking-tight">Charles
                                Kingsley</h4>
                            <span class="text-[10px] uppercase tracking-widest text-luxury-secondary">Analyste Financier</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking CTA Section -->
    <section class="py-24 md:py-36 bg-luxury-bg relative overflow-hidden">
        <!-- Accent light source background effect -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-luxury-gold/5 rounded-full filter blur-[150px] pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3 block">RÉSERVER UN FAUTEUIL</span>
            <h2 class="font-display font-bold text-4xl md:text-6xl uppercase tracking-tighter text-white mb-6">
                PRÊT POUR VOTRE PROCHAIN STYLE ?
            </h2>
            <p class="text-luxury-secondary text-base md:text-lg font-light leading-relaxed max-w-xl mx-auto mb-10">
                Découvrez des soins d'exception avec nos maîtres barbiers. Réservez votre créneau dès maintenant.
            </p>

        </div>
    </section>

    <!-- Contact & Location Info -->
    <section id="contact" class="py-24 bg-luxury-surface border-t border-luxury-border/60">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
                <!-- Location Details: 5 Columns -->
                <div class="lg:col-span-5 flex flex-col justify-between">
                    <div>
                        <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3 block">NOUS TROUVER</span>
                        <h2
                            class="font-display font-bold text-3xl md:text-4xl uppercase tracking-tight text-white mb-8">
                            LE STUDIO
                        </h2>

                        <!-- Contact Info -->
                        <div class="space-y-6 text-luxury-secondary font-light text-sm md:text-base">
                            <p class="flex items-start gap-4">
                                <span
                                    class="text-luxury-gold font-bold font-display uppercase tracking-widest text-xs pt-1">ADR :</span>
                                <a href="https://www.google.com/maps/place/Gentleman+Barber+Studio/@33.5153736,-7.813704,17z/data=!3m1!4b1!4m6!3m5!1s0xda881b612a44599:0x125e64b61e2929f4!8m2!3d33.5153736!4d-7.813704!16s%2Fg%2F11njx9xc5t"
                                    target="_blank" rel="noopener noreferrer"
                                    class="hover:text-luxury-gold transition-colors duration-300">
                                    Gentleman Barber Studio,<br>Casablanca, Maroc
                                </a>
                            </p>
                            <p class="flex items-start gap-4">
                                <span
                                    class="text-luxury-gold font-bold font-display uppercase tracking-widest text-xs pt-1">TÉL :</span>
                                <a href="tel:+212664019364"
                                    class="hover:text-luxury-gold transition-colors duration-300">+212 (0)
                                    664-019364</a>
                            </p>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="mt-12 pt-8 border-t border-luxury-border/60">
                        <h3 class="font-display font-bold text-sm uppercase tracking-wider text-white mb-4">Horaires d'ouverture</h3>
                        <div class="space-y-2 text-xs md:text-sm text-luxury-secondary font-light">
                            <div class="flex justify-between"><span>Lun — Dim</span><span
                                    class="text-white font-medium">9:00 — 21:00</span></div>
                            <div class="text-[11px] text-luxury-gold/90 font-medium tracking-wide uppercase pt-1">Ouvert 7j/7</div>
                        </div>
                    </div>
                </div>

                <!-- Map Frame & Socials: 7 Columns -->
                <div class="lg:col-span-7 flex flex-col gap-8">
                    <!-- Google Map Stylized Frame -->
                    <div
                        class="relative w-full h-[360px] rounded-2xl overflow-hidden border border-luxury-border bg-luxury-bg shadow-xl group">
                        <!-- Embedded Google Map -->
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3326.4619352710097!2d-7.813704!3d33.5153736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda881b612a44599%3A0x125e64b61e2929f4!2sGentleman%20Barber%20Studio!5e0!3m2!1sen!2sma!4v1722860000000!5m2!1sen!2sma"
                            class="w-full h-full border-0 filter invert-[90%] hue-rotate-180 contrast-125 brightness-90 opacity-80 group-hover:opacity-100 transition-opacity duration-500"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>

                        <!-- Floating Badge & Direct Button -->
                        <div class="absolute bottom-4 right-4 z-10">
                            <a href="https://www.google.com/maps/place/Gentleman+Barber+Studio/@33.5153736,-7.813704,17z/data=!3m1!4b1!4m6!3m5!1s0xda881b612a44599:0x125e64b61e2929f4!8m2!3d33.5153736!4d-7.813704!16s%2Fg%2F11njx9xc5t"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 bg-luxury-bg/90 hover:bg-luxury-gold backdrop-blur-md border border-luxury-gold/50 text-luxury-gold hover:text-luxury-bg font-display text-[11px] font-bold uppercase tracking-widest px-5 py-2.5 rounded-full transition-all duration-300 shadow-lg">
                                <span class="text-xs">📍</span> Ouvrir dans Google Maps
                            </a>
                        </div>
                    </div>

                    <!-- Social Handles -->
                    <div
                        class="flex flex-wrap gap-4 items-center justify-between py-4 border-t border-b border-luxury-border/60">
                        <span class="font-display text-xs uppercase tracking-widest text-luxury-secondary">Rejoignez-nous :</span>
                        <div class="flex gap-6 font-display text-xs uppercase tracking-widest text-white">
                            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Instagram</a>
                            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Threads</a>
                            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">YouTube</a>
                            <a href="#" class="hover:text-luxury-gold transition-colors duration-300">Pinterest</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-luxury-bg border-t border-luxury-border/60 py-12">
        <div
            class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <!-- Left Info -->
            <div class="flex flex-col md:flex-row items-center gap-6">
                <a href="#hero"
                    class="font-display font-black text-lg tracking-tighter text-white flex items-center gap-1.5">
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

    <!-- Premium Interactive Booking Modal -->
    <div class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4 md:p-6" x-show="bookingOpen"
        x-transition style="display: none;">

    </div>

</body>

</html>
