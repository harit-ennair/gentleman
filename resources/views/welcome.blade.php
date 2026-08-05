<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GENTLEMAN | Luxury Barbershop & Grooming Parlor</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Experience premium grooming at Gentleman. Modern craftsmanship, luxury services, and classic styles designed for the contemporary man.">
    <meta name="keywords" content="barber, luxury barber, grooming, haircut, beard trim, royal shave, gentleman">

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
      }" 
      @scroll.window="scrolled = (window.pageYOffset > 50) ? true : false">

    <!-- Transparent Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 border-b border-transparent"
         :class="scrolled ? 'bg-luxury-bg/95 backdrop-blur-md py-4 border-luxury-border/60' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex items-center justify-between">
            <!-- Logo -->
            <a href="#hero" class="font-display font-black text-2xl tracking-tighter text-luxury-primary flex items-center gap-2 group">
                <span class="text-luxury-gold transform group-hover:rotate-12 transition-transform duration-300">◆</span>
                GENTLEMAN
            </a>

            <!-- Navigation Links -->
            <div class="hidden lg:flex items-center gap-10 font-display text-xs uppercase tracking-widest text-luxury-secondary">
                <a href="#about" class="hover:text-luxury-gold transition-colors duration-300">Who We Are</a>
                <a href="#services" class="hover:text-luxury-gold transition-colors duration-300">Services</a>
                <a href="#why-choose" class="hover:text-luxury-gold transition-colors duration-300">Pillars</a>
                <a href="#products" class="hover:text-luxury-gold transition-colors duration-300">Shop</a>
                <a href="#gallery" class="hover:text-luxury-gold transition-colors duration-300">Gallery</a>
                <a href="#testimonials" class="hover:text-luxury-gold transition-colors duration-300">Reviews</a>
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
                    <button @click="toggleTheme()" 
                            type="button"
                            class="flex items-center justify-center w-9 h-9 rounded-full border border-luxury-border bg-luxury-surface/80 text-luxury-primary hover:text-luxury-gold hover:border-luxury-gold transition-all duration-300 shadow-sm cursor-pointer"
                            :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
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

                <!-- Cart Status Indicator -->
                <div class="relative cursor-pointer hover:text-luxury-gold transition-colors duration-300 mr-2" 
                     x-show="cartCount > 0"
                     x-transition
                     @click="alert('Proceeding to luxury cart...')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-luxury-gold text-luxury-bg text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center" x-text="cartCount"></span>
                </div>

                <button @click="bookingOpen = true" 
                        class="bg-luxury-primary text-luxury-bg hover:bg-luxury-gold hover:text-luxury-bg px-6 py-2.5 rounded-full text-xs font-display font-bold uppercase tracking-wider transition-all duration-500 shadow-md">
                    Book Appointment
                </button>
            </div>
        </div>
    </nav>

    <!-- Cart Add Toast Notification -->
    <div class="fixed bottom-6 right-6 z-50 bg-luxury-surface border border-luxury-gold text-luxury-primary px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-500 transform translate-y-0"
         x-show="cartMessage !== ''"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4">
        <span class="text-luxury-gold">✔</span>
        <span class="text-sm font-semibold" x-text="cartMessage"></span>
    </div>

    <!-- Hero Section -->
    <section id="hero" class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden">
        <!-- Cinematic Background Video -->
        <div id="hero-video-bg" class="absolute inset-0 z-0">
            <video class="w-full h-full object-cover" 
                   autoplay 
                   loop 
                   muted 
                   playsinline
                   preload="auto"
                   poster="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=1950&q=80">
                <source src="/videos/hero-bg.mp4" type="video/mp4">
            </video>
            <!-- Dark overlay for text readability (50%) -->
            <div class="absolute inset-0" style="background-color: rgba(0,0,0,0.5)"></div>
            <!-- Gradient overlay for depth -->
            <div class="absolute inset-0" style="background: linear-gradient(to top, #0B0B0B, transparent 60%, rgba(0,0,0,0.3))"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 text-center flex flex-col items-center mt-16">
            <!-- Editorial Subtitle -->
            <div class="inline-flex items-center gap-3 text-luxury-gold uppercase tracking-[0.3em] text-xs font-display mb-6 animate-fade-in">
                <span class="h-px w-8 bg-luxury-gold"></span>
                Est. 2026 • Barber & Grooming Parlor
                <span class="h-px w-8 bg-luxury-gold"></span>
            </div>

            <!-- Massive Header -->
            <h1 class="font-display font-black text-6xl md:text-8xl lg:text-[110px] leading-[0.9] uppercase tracking-tighter text-white max-w-5xl mb-8 animate-fade-up">
                CRAFTING<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-luxury-primary via-luxury-gold to-luxury-primary">TIMELESS</span><br>
                STYLE
            </h1>

            <!-- Description -->
            <p class="text-luxury-secondary text-lg md:text-xl font-light max-w-2xl leading-relaxed mb-10 animate-fade-up" style="animation-delay: 150ms">
                Premium grooming experiences for the modern gentleman. We merge classical heritage with contemporary style to forge unforgettable character.
            </p>

            <!-- Dual CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-up" style="animation-delay: 300ms">
                <button @click="bookingOpen = true" 
                        class="bg-luxury-gold text-luxury-bg hover:bg-luxury-primary hover:text-luxury-bg px-8 py-4 rounded-full text-xs font-display font-bold uppercase tracking-widest transition-all duration-500 w-full sm:w-auto">
                    Book Appointment
                </button>
                <a href="#services" 
                   class="border border-luxury-border hover:border-luxury-gold px-8 py-4 rounded-full text-xs font-display font-bold uppercase tracking-widest text-luxury-primary transition-all duration-500 w-full sm:w-auto text-center backdrop-blur-sm">
                    Explore Services
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <a href="#about" class="absolute bottom-10 z-10 flex flex-col items-center gap-2 group animate-bounce">
            <span class="text-[9px] uppercase tracking-[0.2em] font-display text-luxury-secondary group-hover:text-luxury-gold transition-colors duration-300">Scroll Down</span>
            <svg class="h-4 w-4 text-luxury-secondary group-hover:text-luxury-gold transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">WHO WE ARE</span>
                    <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl leading-tight uppercase tracking-tight text-white mb-8">
                        THE ART OF<br>
                        MODERN MASCULINITY
                    </h2>
                    <p class="text-luxury-secondary text-base md:text-lg font-light leading-relaxed mb-6">
                        Gentleman is more than a barbershop; it is an editorial sanctuary for the modern man. Built on the principles of sharp geometry, custom grooming, and unmatched precision, our master barbers cultivate a tailored signature for each client.
                    </p>
                    <p class="text-luxury-secondary/80 text-sm md:text-base font-light leading-relaxed mb-8">
                        Every service begins with a personal styling audit. We select premium organic tonics, evaluate your structure, and sculpt matching outlines that frame your unique personality. Step into our world of sensory indulgence.
                    </p>

                    <!-- Quote -->
                    <div class="border-l-2 border-luxury-gold pl-6 py-2 mb-8 bg-luxury-surface/30 pr-4 rounded-r-xl border-t border-b border-r border-luxury-border/10">
                        <p class="text-sm md:text-base font-serif italic text-luxury-primary leading-relaxed">
                            "Style is the signature of your self-respect. Our goal is to make that signature sharp, elegant, and absolutely unmistakable."
                        </p>
                        <span class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display mt-2">— Alexander Mercer, Lead Master Barber</span>
                    </div>
                </div>

                <!-- Right Images: 6 Columns -->
                <div class="lg:col-span-6 grid grid-cols-12 gap-6 relative">
                    <!-- Accent decorative background frame -->
                    <div class="absolute -inset-4 border border-luxury-gold/10 rounded-3xl pointer-events-none -z-10 transform rotate-1"></div>

                    <!-- Left larger image -->
                    <div class="col-span-8 overflow-hidden rounded-2xl border border-luxury-border shadow-2xl relative group">
                        <img src="https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=800&q=80" 
                             class="w-full h-[400px] object-cover filter grayscale hover:grayscale-0 transition-all duration-700 ease-in-out transform hover:scale-105" 
                             alt="Styling in progress">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                            <span class="text-xs uppercase tracking-widest text-luxury-gold font-display font-semibold">Focused Craftsmanship</span>
                        </div>
                    </div>

                    <!-- Right offset smaller image -->
                    <div class="col-span-4 self-end overflow-hidden rounded-xl border border-luxury-border shadow-2xl relative group mb-8">
                        <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=600&q=80" 
                             class="w-full h-[220px] object-cover filter grayscale hover:grayscale-0 transition-all duration-700 ease-in-out transform hover:scale-105" 
                             alt="Luxury chair details">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-[9px] uppercase tracking-widest text-luxury-gold font-display font-semibold">Premium Station</span>
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
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">EXPERIENCES</span>
                    <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tight text-white">
                        THE EXPERIENCES
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                        A carefully curated list of treatments designed to sculpt character, restore energy, and sharpen your appearance.
                    </p>
                </div>
            </div>

            <!-- Service Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($services as $service)
                    <!-- Dynamic service card -->
                    <div class="group bg-luxury-bg border border-luxury-border/60 rounded-2xl overflow-hidden hover:border-luxury-gold/50 transition-all duration-500 flex flex-col h-full shadow-lg">
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
                            <div class="absolute inset-0 bg-gradient-to-t from-luxury-bg via-transparent to-transparent opacity-60"></div>
                            
                            <!-- Category/Badge -->
                            <div class="absolute top-4 right-4 bg-luxury-bg/85 border border-luxury-gold/30 px-3 py-1 rounded-full text-[9px] uppercase tracking-widest text-luxury-gold font-display backdrop-blur-sm">
                                Premium Service
                            </div>
                        </div>

                        <!-- Card details -->
                        <div class="p-6 md:p-8 flex flex-col flex-grow">
                            <div class="flex items-baseline justify-between mb-4">
                                <h3 class="font-display font-bold text-xl text-white group-hover:text-luxury-gold transition-colors duration-300 uppercase tracking-tight">
                                    {{ $service->name }}
                                </h3>
                                <span class="text-xl font-display font-semibold text-luxury-gold">DH {{ number_format($service->price, 0) }}</span>
                            </div>

                            <p class="text-luxury-secondary text-sm font-light leading-relaxed mb-6 flex-grow">
                                {{ $service->description }}
                            </p>

                            <div class="pt-6 border-t border-luxury-border/60 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-xs text-luxury-secondary font-display uppercase tracking-wider">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-luxury-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $service->duration }} Mins
                                </span>

                                <a href="{{ route('appointments.create', ['service_id' => $service->id]) }}" 
                                   class="text-xs uppercase tracking-widest font-display text-white group-hover:text-luxury-gold transition-colors duration-300 font-bold flex items-center gap-1">
                                    Book Now <span>→</span>
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
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">OUR MANIFESTO</span>
                    <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tighter leading-tight text-white mb-6">
                        THE GENTLEMAN<br>
                        STANDARD
                    </h2>
                    <p class="text-luxury-secondary text-base font-light leading-relaxed mb-8">
                        We hold our services to an extreme standard of visual performance and customer care. Each detail is curated to build a flawless grooming environment.
                    </p>
                    
                    <!-- Decorative graphics -->
                    <div class="hidden lg:block relative h-48 w-full border border-luxury-border rounded-2xl overflow-hidden bg-luxury-surface/20">
                        <div class="absolute inset-0 bg-cover bg-center filter grayscale opacity-40 hover:opacity-85 transition-opacity duration-700"
                             style="background-image: url('https://images.unsplash.com/photo-1512690196222-7c7c72491214?auto=format&fit=crop&w=600&q=80')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-luxury-bg to-transparent"></div>
                        <div class="absolute bottom-4 left-4 font-display text-[10px] tracking-widest text-luxury-gold uppercase font-bold">Signature Blades Only</div>
                    </div>
                </div>

                <!-- Right reasons list: 7 Columns -->
                <div class="lg:col-span-7 divide-y divide-luxury-border/60">
                    <!-- Item 1 -->
                    <div class="py-8 first:pt-0">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">01</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">Professional Barbers</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Our master barbers are selected for their technical expertise, deep knowledge of hair geometry, and commitment to the traditional craft of grooming.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">02</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">Premium Products</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    We exclusively formulate and curates organic oils, hair tonics, pomades, and facial clays made with plant-based luxury ingredients.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">03</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">Luxury Experience</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Enjoy premium single-origin coffee, aged single-malt whiskey, classical record selections, and heated leather chairs during your treatments.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 4 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">04</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">Modern Equipment</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    High-performance tools, sanitized custom blades, Japanese hair wash basins, and advanced facial steamers for premium comfort.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 5 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">05</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">Clean Environment</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    We follow surgical-grade sanitation protocols. Every razor, comb, and seat is thoroughly sterilized between clients.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Item 6 -->
                    <div class="py-8">
                        <div class="flex gap-6 items-start">
                            <span class="font-display font-bold text-2xl text-luxury-gold">06</span>
                            <div>
                                <h3 class="font-display font-bold text-xl uppercase tracking-tight text-white mb-3">Personalized Service</h3>
                                <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                                    Your grooming logs are archived. We track your hairline growth patterns, product sensitivities, and preferred blade sizes across visits.
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
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3">CURATED SHOP</span>
                    <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tight text-white">
                        THE COLLECTION
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                        Take the Gentleman experience home. Our signature styling and beard grooming formulations are mixed by hand and packaged in dark glass.
                    </p>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($latestProducts as $product)
                    <!-- Dynamic Product Card -->
                    <div class="group bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/50 rounded-2xl overflow-hidden transition-all duration-500 flex flex-col h-full shadow-lg">
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
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                <button @click="cartCount++; cartMessage = 'Added ' + '{{ $product->name }}' + ' to cart'; setTimeout(() => cartMessage = '', 3000)" 
                                        class="bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg px-6 py-3 rounded-full text-xs font-display font-bold uppercase tracking-wider transition-all duration-300">
                                    Quick Add • {{ number_format($product->price, 0) }} DH
                                </button>
                            </div>

                            <span class="absolute top-4 left-4 bg-luxury-surface/80 border border-luxury-border px-2.5 py-0.5 rounded-full text-[9px] uppercase tracking-widest text-luxury-secondary font-display">
                                {{ $product->category->name ?? 'Collection' }}
                            </span>
                        </div>

                        <!-- Content Details -->
                        <div class="p-6 flex flex-col flex-grow border-t border-luxury-border/30">
                            <h3 class="font-display font-bold text-lg text-white mb-2 group-hover:text-luxury-gold transition-colors duration-300 uppercase tracking-tight">
                                {{ $product->name }}
                            </h3>
                            <p class="text-luxury-secondary/85 text-xs font-light leading-relaxed mb-6 flex-grow">
                                {{ $product->description }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-luxury-border/30">
                                <span class="text-base font-display font-bold text-luxury-gold">{{ number_format($product->price, 0) }} DH</span>
                                <button @click="cartCount++; cartMessage = 'Added ' + '{{ $product->name }}' + ' to cart'; setTimeout(() => cartMessage = '', 3000)"
                                        class="text-xs uppercase tracking-widest font-display text-white hover:text-luxury-gold transition-colors duration-300 font-bold flex items-center gap-1">
                                    Add to Cart <span>+</span>
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
                    <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl uppercase tracking-tight text-white">
                        THE GALLERY
                    </h2>
                </div>
                <div class="max-w-md">
                    <p class="text-luxury-secondary text-sm md:text-base font-light leading-relaxed">
                        A visual record of our craftsmanship and editorial styling. Explore the sharp profiles forged within our studios.
                    </p>
                </div>
            </div>

            <!-- Masonry Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $galleryImages = [
                        ['src' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=800&q=80', 'title' => 'Signature Fade & Trim'],
                        ['src' => 'https://images.unsplash.com/photo-1622287198514-5d10f64aaab7?auto=format&fit=crop&w=800&q=80', 'title' => 'Hot Towel Detailing'],
                        ['src' => 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=800&q=80', 'title' => 'Luxury Grooming Kit'],
                        ['src' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80', 'title' => 'Traditional Hot Lather'],
                        ['src' => 'https://images.unsplash.com/photo-1512690196222-7c7c72491214?auto=format&fit=crop&w=800&q=80', 'title' => 'The Straight Razor Lineup'],
                        ['src' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80', 'title' => 'The Gentleman Lounge']
                    ];
                @endphp

                @foreach ($galleryImages as $index => $item)
                    <div class="group relative overflow-hidden rounded-2xl border border-luxury-border/60 aspect-square cursor-pointer shadow-lg"
                         @click="activeGalleryImage = '{{ $item['src'] }}'">
                        <img src="{{ $item['src'] }}" 
                             class="w-full h-full object-cover filter grayscale hover:grayscale-0 transform hover:scale-105 transition-all duration-700 ease-in-out" 
                             alt="{{ $item['title'] }}">
                        <div class="absolute inset-0 bg-black/75 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6 border border-luxury-gold/20 rounded-2xl">
                            <span class="text-luxury-gold font-display text-[10px] tracking-[0.2em] uppercase mb-1">Interactive View</span>
                            <h4 class="font-display font-bold text-lg text-white uppercase tracking-tight">{{ $item['title'] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Fullscreen Gallery Modal Overlay -->
        <div class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-6" 
             x-show="activeGalleryImage !== null"
             x-transition
             @click="activeGalleryImage = null"
             style="display: none;">
            <button class="absolute top-6 right-6 text-white hover:text-luxury-gold transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img :src="activeGalleryImage" 
                 class="max-w-full max-h-[90vh] object-contain rounded-2xl border border-luxury-border" 
                 alt="Enlarged View">
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-24 md:py-36 bg-luxury-surface border-b border-luxury-border/30">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <!-- Header -->
            <div class="text-center max-w-2xl mx-auto mb-20">
                <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3 flex items-center justify-center gap-2">
                    <span class="h-px w-6 bg-luxury-gold"></span>
                    TESTIMONIALS
                    <span class="h-px w-6 bg-luxury-gold"></span>
                </span>
                <h2 class="font-display font-bold text-4xl md:text-5xl uppercase tracking-tighter text-white">
                    WHAT THEY SAY
                </h2>
            </div>

            <!-- Testimonial Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/30 rounded-2xl p-8 transition-all duration-500 shadow-md relative">
                    <div class="text-luxury-gold flex gap-1 mb-6 text-sm">★★★★★</div>
                    <p class="text-luxury-secondary text-sm md:text-base font-light italic leading-relaxed mb-8">
                        "The styling audit completely transformed my look. They analyzed my face shape and suggested a classic crop that suits me perfectly. The atmosphere is premium and relax."
                    </p>
                    <div class="flex items-center gap-4 pt-6 border-t border-luxury-border/40">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80" 
                             class="h-12 w-12 rounded-full object-cover filter grayscale" 
                             alt="David Sterling">
                        <div>
                            <h4 class="font-display font-bold text-sm uppercase text-white tracking-tight">David Sterling</h4>
                            <span class="text-[10px] uppercase tracking-widest text-luxury-secondary">Entrepreneur</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/30 rounded-2xl p-8 transition-all duration-500 shadow-md relative">
                    <div class="text-luxury-gold flex gap-1 mb-6 text-sm">★★★★★</div>
                    <p class="text-luxury-secondary text-sm md:text-base font-light italic leading-relaxed mb-8">
                        "The Royal Shave is a legendary experience. Pre-shave essential oils, thick lather, hot towels, and the straight razor finish. An absolute luxury routine I do monthly."
                    </p>
                    <div class="flex items-center gap-4 pt-6 border-t border-luxury-border/40">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&h=150&q=80" 
                             class="h-12 w-12 rounded-full object-cover filter grayscale" 
                             alt="James Harrison">
                        <div>
                            <h4 class="font-display font-bold text-sm uppercase text-white tracking-tight">James Harrison</h4>
                            <span class="text-[10px] uppercase tracking-widest text-luxury-secondary">Creative Director</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-luxury-bg border border-luxury-border/60 hover:border-luxury-gold/30 rounded-2xl p-8 transition-all duration-500 shadow-md relative">
                    <div class="text-luxury-gold flex gap-1 mb-6 text-sm">★★★★★</div>
                    <p class="text-luxury-secondary text-sm md:text-base font-light italic leading-relaxed mb-8">
                        "Unrivaled precision. My barber evaluated the growth direction of my hair and completed a sharp skin fade. Recommended for any gentleman seeking perfection."
                    </p>
                    <div class="flex items-center gap-4 pt-6 border-t border-luxury-border/40">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&h=150&q=80" 
                             class="h-12 w-12 rounded-full object-cover filter grayscale" 
                             alt="Charles Kingsley">
                        <div>
                            <h4 class="font-display font-bold text-sm uppercase text-white tracking-tight">Charles Kingsley</h4>
                            <span class="text-[10px] uppercase tracking-widest text-luxury-secondary">Financial Analyst</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking CTA Section -->
    <section class="py-24 md:py-36 bg-luxury-bg relative overflow-hidden">
        <!-- Accent light source background effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-luxury-gold/5 rounded-full filter blur-[150px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3 block">RESERVE A CHAIR</span>
            <h2 class="font-display font-bold text-4xl md:text-6xl uppercase tracking-tighter text-white mb-6">
                READY FOR YOUR NEXT LOOK?
            </h2>
            <p class="text-luxury-secondary text-base md:text-lg font-light leading-relaxed max-w-xl mx-auto mb-10">
                Experience premium grooming with our master barbers. Secure your preferred slot now.
            </p>
            <button @click="bookingOpen = true" 
                    class="bg-luxury-primary text-luxury-bg hover:bg-luxury-gold hover:text-luxury-bg px-10 py-5 rounded-full text-xs font-display font-bold uppercase tracking-widest transition-all duration-500 shadow-2xl">
                Book Appointment
            </button>
        </div>
    </section>

    <!-- Contact & Location Info -->
    <section id="contact" class="py-24 bg-luxury-surface border-t border-luxury-border/60">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
                <!-- Location Details: 5 Columns -->
                <div class="lg:col-span-5 flex flex-col justify-between">
                    <div>
                        <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-3 block">FIND US</span>
                        <h2 class="font-display font-bold text-3xl md:text-4xl uppercase tracking-tight text-white mb-8">
                            THE STUDIO
                        </h2>
                        
                        <!-- Contact Info -->
                        <div class="space-y-6 text-luxury-secondary font-light text-sm md:text-base">
                            <p class="flex items-start gap-4">
                                <span class="text-luxury-gold font-bold font-display uppercase tracking-widest text-xs pt-1">ADDR:</span>
                                <span>Gentleman Barber Studio,<br>Casablanca, Morocco</span>
                            </p>
                            <p class="flex items-start gap-4">
                                <span class="text-luxury-gold font-bold font-display uppercase tracking-widest text-xs pt-1">TELE:</span>
                                <a href="tel:+212522998877" class="hover:text-luxury-gold transition-colors duration-300">+212 (0) 522-998877</a>
                            </p>
                            <p class="flex items-start gap-4">
                                <span class="text-luxury-gold font-bold font-display uppercase tracking-widest text-xs pt-1">MAIL:</span>
                                <a href="mailto:appointments@gentleman.com" class="hover:text-luxury-gold transition-colors duration-300">appointments@gentleman.com</a>
                            </p>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="mt-12 pt-8 border-t border-luxury-border/60">
                        <h3 class="font-display font-bold text-sm uppercase tracking-wider text-white mb-4">Working Hours</h3>
                        <div class="space-y-2 text-xs md:text-sm text-luxury-secondary font-light">
                            <div class="flex justify-between"><span>Mon — Fri</span><span class="text-white">9:00 AM — 8:00 PM</span></div>
                            <div class="flex justify-between"><span>Saturday</span><span class="text-white">9:00 AM — 6:00 PM</span></div>
                            <div class="flex justify-between"><span>Sunday</span><span class="text-luxury-gold font-semibold uppercase tracking-widest">Closed</span></div>
                        </div>
                    </div>
                </div>

                <!-- Map Frame & Socials: 7 Columns -->
                <div class="lg:col-span-7 flex flex-col gap-8">
                    <!-- Google Map Stylized Placeholder Frame -->
                    <div class="relative w-full h-[320px] rounded-2xl overflow-hidden border border-luxury-border bg-luxury-bg shadow-xl">
                        <!-- Custom dark-mode style map element -->
                        <div class="absolute inset-0 bg-[#16161a] flex flex-col items-center justify-center p-8 text-center">
                            <!-- Golden Pin representation -->
                            <div class="h-10 w-10 bg-luxury-gold/15 rounded-full flex items-center justify-center border border-luxury-gold mb-4 animate-bounce">
                                <span class="text-luxury-gold text-lg">✦</span>
                            </div>
                            <h4 class="font-display font-bold uppercase text-white tracking-tight mb-2">Gentleman Barber Studio</h4>
                            <p class="text-luxury-secondary text-xs max-w-sm mb-6 leading-relaxed">
                                Located in Casablanca, Morocco. Premium grooming experience and private parking available.
                            </p>
                            <a href="https://maps.app.goo.gl/8mEX1Ks4xBsDTyFs8" target="_blank"
                               class="inline-block bg-luxury-surface hover:bg-luxury-gold border border-luxury-border hover:border-luxury-gold text-white hover:text-luxury-bg font-display text-[10px] font-bold uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-300">
                                Open in Google Maps
                            </a>
                        </div>
                    </div>

                    <!-- Social Handles -->
                    <div class="flex flex-wrap gap-4 items-center justify-between py-4 border-t border-b border-luxury-border/60">
                        <span class="font-display text-xs uppercase tracking-widest text-luxury-secondary">Connect with us:</span>
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
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <!-- Left Info -->
            <div class="flex flex-col md:flex-row items-center gap-6">
                <a href="#hero" class="font-display font-black text-lg tracking-tighter text-white flex items-center gap-1.5">
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

    <!-- Premium Interactive Booking Modal -->
    <div class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4 md:p-6"
         x-show="bookingOpen"
         x-transition
         style="display: none;">
        
        <!-- Modal Card Container -->
        <div class="bg-luxury-surface border border-luxury-gold/30 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl relative transition-all transform scale-100"
             @click.away="bookingOpen = false; bookingSuccess = false">
            
            <!-- Close Button -->
            <button @click="bookingOpen = false; bookingSuccess = false" 
                    class="absolute top-4 right-4 text-luxury-secondary hover:text-white transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Content Grid -->
            <div class="p-8">
                <!-- Header -->
                <div class="text-center mb-8" x-show="!bookingSuccess">
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-[10px] font-display mb-1 block">RESERVATION</span>
                    <h3 class="font-display font-bold text-2xl uppercase text-white tracking-tight">BOOK A SESSION</h3>
                    <p class="text-luxury-secondary text-xs font-light mt-1">Select your treatment, date and time below.</p>
                </div>

                <!-- Form Section -->
                <form @submit.prevent="bookingSuccess = true" x-show="!bookingSuccess" class="space-y-6">
                    <!-- Service Select -->
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2">Select Service</label>
                        <select x-model="selectedService" required
                                class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 appearance-none cursor-pointer">
                            <option value="">Choose a treatment...</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->name }}">{{ $service->name }} — {{ number_format($service->price, 0) }} DH</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grid: Date and Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2">Date</label>
                            <input type="date" x-model="selectedDate" required
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2">Time Slot</label>
                            <select x-model="selectedTime" required
                                    class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 appearance-none cursor-pointer">
                                <option value="">Select time...</option>
                                <option value="09:00 AM">09:00 AM</option>
                                <option value="10:30 AM">10:30 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="01:30 PM">01:30 PM</option>
                                <option value="03:00 PM">03:00 PM</option>
                                <option value="04:30 PM">04:30 PM</option>
                                <option value="06:00 PM">06:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2">First Name</label>
                            <input type="text" placeholder="e.g. John" required
                                   class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2">Last Name</label>
                            <input type="text" placeholder="e.g. Doe" required
                                   class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-luxury-gold font-display font-semibold mb-2">Email Address</label>
                        <input type="email" placeholder="john.doe@example.com" required
                               class="w-full bg-luxury-bg border border-luxury-border rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-luxury-gold transition-colors duration-300 placeholder:text-luxury-secondary/40">
                    </div>

                    <!-- Submit -->
                    <button type="submit" 
                            class="w-full bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg font-display text-xs font-bold uppercase tracking-widest py-4 rounded-xl transition-all duration-300 shadow-lg mt-4">
                        Confirm Appointment
                    </button>
                </form>

                <!-- Success Screen -->
                <div class="text-center py-12 space-y-6" x-show="bookingSuccess" style="display: none;">
                    <div class="h-16 w-16 bg-luxury-gold/15 rounded-full flex items-center justify-center border border-luxury-gold mx-auto mb-4">
                        <span class="text-luxury-gold text-2xl">✔</span>
                    </div>
                    <h3 class="font-display font-bold text-2xl uppercase text-white tracking-tight">RESERVATION CONFIRMED</h3>
                    <div class="bg-luxury-bg border border-luxury-border p-6 rounded-2xl text-left space-y-3 text-xs max-w-sm mx-auto">
                        <div class="flex justify-between"><span class="text-luxury-secondary">Treatment:</span><span class="text-white font-bold" x-text="selectedService"></span></div>
                        <div class="flex justify-between"><span class="text-luxury-secondary">Date:</span><span class="text-white font-bold" x-text="selectedDate"></span></div>
                        <div class="flex justify-between"><span class="text-luxury-secondary">Time Slot:</span><span class="text-white font-bold" x-text="selectedTime"></span></div>
                        <div class="flex justify-between"><span class="text-luxury-secondary">Location:</span><span class="text-luxury-gold font-bold">Beverly Hills Parlor</span></div>
                    </div>
                    <p class="text-luxury-secondary text-xs max-w-xs mx-auto leading-relaxed">
                        A verification email and calendar invite has been sent to your email. We look forward to your session.
                    </p>
                    <button @click="bookingOpen = false; bookingSuccess = false" 
                            class="bg-luxury-gold text-luxury-bg hover:bg-white hover:text-luxury-bg font-display text-xs font-bold uppercase tracking-widest px-8 py-3 rounded-full transition-all duration-300">
                        Return to Site
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
