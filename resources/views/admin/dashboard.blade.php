@extends('layouts.test')

@section('content')
    <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ activeTab: 'overview' }">
        <!-- Left Sidebar Column (3 cols) -->
        <div class="lg:col-span-3">
            <div class="bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 lg:sticky lg:top-28 space-y-6">
                <!-- Sidebar Header -->
                <div>
                    <span
                        class="text-luxury-gold uppercase tracking-[0.2em] text-[10px] font-display block mb-1">Navigation</span>
                    <h3 class="font-display font-black text-lg uppercase tracking-tight text-white flex items-center gap-2">
                        <span class="text-luxury-gold">◆</span> Menu Administration
                    </h3>
                </div>

                <!-- Navigation Links -->
                <nav class="flex flex-col gap-2">
                    <!-- Overview Tab Link -->
                    <button @click="activeTab = 'overview'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'overview' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                        <span class="text-sm"></span> Aperçu
                    </button>

                    <!-- Categories Tab Link -->
                    <button @click="activeTab = 'categories'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'categories' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                        <span class="text-sm"></span> Catégories
                    </button>

                    <!-- Services Tab Link -->
                    <button @click="activeTab = 'services'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'services' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                        <span class="text-sm"></span> Services
                    </button>

                    <!-- Products Tab Link -->
                    <button @click="activeTab = 'products'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'products' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                        <span class="text-sm"></span> Produits
                    </button>
                </nav>

                <!-- Separator -->
                <div class="h-px bg-luxury-border/60"></div>

                <!-- External Pages Links -->
                <div class="space-y-3">
                    <span
                        class="text-luxury-secondary/50 uppercase tracking-[0.2em] text-[9px] font-display block px-1">Gérer les ressources</span>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-display font-semibold uppercase tracking-wider text-luxury-secondary hover:text-luxury-gold hover:bg-luxury-bg/30 transition-all duration-300">
                            <span class="text-sm"></span> Utilisateurs
                        </a>
                        <a href="{{ route('admin.appointments.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-display font-semibold uppercase tracking-wider text-luxury-secondary hover:text-luxury-gold hover:bg-luxury-bg/30 transition-all duration-300">
                            <span class="text-sm"></span> Rendez-vous
                        </a>
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-display font-semibold uppercase tracking-wider text-luxury-secondary hover:text-luxury-gold hover:bg-luxury-bg/30 transition-all duration-300">
                            <span class="text-sm"></span> Commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content Column (9 cols) -->
        <div class="lg:col-span-9 space-y-8">
            <!-- Page Title Banner (Dynamic title based on tab) -->
            <div class="border-b border-luxury-border/60 pb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-2 block">Panneau de contrôle</span>
                    <h1 class="font-display font-black text-4xl md:text-5xl uppercase tracking-tighter text-white">
                        <span x-show="activeTab === 'overview'">Aperçu du tableau de bord</span>
                        <span x-show="activeTab === 'categories'" style="display: none;">Gestion des catégories</span>
                        <span x-show="activeTab === 'services'" style="display: none;">Gestion des services</span>
                        <span x-show="activeTab === 'products'" style="display: none;">Gestion des produits</span>
                    </h1>
                </div>
                <!-- Quick Actions -->
                <div class="flex items-center gap-2 text-xs">
                    <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-luxury-secondary font-display uppercase tracking-widest text-[10px]">Admin Actif</span>
                </div>
            </div>

            <!-- TAB CONTENT: OVERVIEW -->
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300"
                class="w-full space-y-8">
                <!-- Stats Cards Grid -->
                <div class="w-full grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    @foreach([
                            'Clients' => ['value' => $clientsCount],
                            'Rendez-vous' => ['value' => $appointmentsCount],
                            'Commandes' => ['value' => $ordersCount],
                            'CA Aujourd\'hui' => ['value' => number_format($todayRevenue, 0) . ' DH'],
                            'CA du Mois' => ['value' => number_format($monthlyRevenue, 0) . ' DH']
                        ] as $label => $data)
                        <div
                            class="group bg-luxury-surface border border-luxury-border/60 hover:border-luxury-gold/40 rounded-2xl p-5 transition-all duration-500 shadow-xl flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <span
                                    class="text-luxury-secondary text-[10px] uppercase tracking-wider font-display">{{ $label }}</span>
                            </div>
                            <p
                                class="text-2xl font-display font-bold text-white tracking-tight group-hover:text-luxury-gold transition-colors duration-300">
                                {{ $data['value'] }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <!-- Interactive Analytics & Performance Charts Grid -->
                <div class="w-full grid gap-8 grid-cols-1 lg:grid-cols-12">
                    <!-- Monthly Revenue & Sales Growth Chart (8 cols) -->
                    <div class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-6 sm:p-7 shadow-2xl flex flex-col justify-between" x-data="{ timeframe: 'year' }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-luxury-border/40 pb-4 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-display font-bold text-base uppercase tracking-tight text-white">Croissance des ventes & Chiffre d'affaires</h3>
                                    <p id="revenueChartSubtitle" class="text-xs text-luxury-secondary">Performance des ventes mensuelles (DH) pour {{ now()->year }}</p>
                                </div>
                            </div>

                            <!-- Timeframe Selector Tabs (Week / Month / Year) -->
                            <div class="flex items-center gap-1 rounded-2xl border border-luxury-border bg-luxury-bg/50 p-1.5 shadow-inner self-start sm:self-auto">
                                <button type="button" @click="timeframe = 'week'; updateRevenueChart('week')"
                                        :class="timeframe === 'week' ? 'bg-luxury-gold text-black shadow-md font-bold' : 'text-luxury-secondary hover:text-white'"
                                        class="rounded-xl px-3.5 py-1.5 text-[11px] font-display uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                    Semaine
                                </button>
                                <button type="button" @click="timeframe = 'month'; updateRevenueChart('month')"
                                        :class="timeframe === 'month' ? 'bg-luxury-gold text-black shadow-md font-bold' : 'text-luxury-secondary hover:text-white'"
                                        class="rounded-xl px-3.5 py-1.5 text-[11px] font-display uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                    Mois
                                </button>
                                <button type="button" @click="timeframe = 'year'; updateRevenueChart('year')"
                                        :class="timeframe === 'year' ? 'bg-luxury-gold text-black shadow-md font-bold' : 'text-luxury-secondary hover:text-white'"
                                        class="rounded-xl px-3.5 py-1.5 text-[11px] font-display uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                    Année
                                </button>
                            </div>
                        </div>

                        <div class="relative h-64 sm:h-72 w-full">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Appointments Status Breakdown Donut Chart (4 cols) -->
                    <div class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-6 sm:p-7 shadow-2xl flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-luxury-border/40 pb-4 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-display font-bold text-base uppercase tracking-tight text-white">Rendez-vous</h3>
                                    <p class="text-xs text-luxury-secondary">Répartition par statut</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative h-56 sm:h-60 w-full flex items-center justify-center">
                            <canvas id="appointmentsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Sections -->
                <div class="w-full grid gap-8 grid-cols-1 lg:grid-cols-2">
                    <!-- Recent Appointments -->
                    <div
                        class="bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[380px]">
                        <h3
                            class="font-display font-bold text-base uppercase tracking-tight text-white mb-4 flex items-center gap-2 pb-2 border-b border-luxury-border/40">
                            <span class="text-luxury-gold">◆</span> Rendez-vous récents
                        </h3>
                        <div class="overflow-y-auto grow custom-scrollbar space-y-3 pr-1">
                            @forelse($latestAppointments as $appointment)
                                <div
                                    class="p-3 bg-luxury-bg/40 border border-luxury-border/50 rounded-xl flex items-center justify-between text-xs transition-colors duration-300 hover:border-luxury-gold/20">
                                    <div>
                                        <p class="font-semibold text-white">{{ $appointment->user->full_name }}</p>
                                        <p class="text-luxury-secondary text-[10px] mt-0.5">{{ $appointment->service->name }} •
                                            {{ $appointment->appointment_at->locale('fr')->isoFormat('D MMM, HH:mm') }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[8px] font-display font-bold uppercase 
                                            @if($appointment->status->value === 'pending') bg-yellow-950/40 text-yellow-400 border border-yellow-800/30
                                            @elseif($appointment->status->value === 'confirmed') bg-green-950/40 text-green-400 border border-green-800/30
                                            @elseif($appointment->status->value === 'completed') bg-blue-950/40 text-blue-400 border border-blue-800/30
                                            @else bg-red-950/40 text-red-400 border border-red-900/30 @endif">
                                        {{ $appointment->status->label() }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-luxury-secondary text-xs italic text-center py-8">Aucun rendez-vous trouvé.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div
                        class="bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[380px]">
                        <h3
                            class="font-display font-bold text-base uppercase tracking-tight text-white mb-4 flex items-center gap-2 pb-2 border-b border-luxury-border/40">
                            <span class="text-luxury-gold">◆</span> Commandes récentes
                        </h3>
                        <div class="overflow-y-auto grow custom-scrollbar space-y-3 pr-1">
                            @forelse($latestOrders as $order)
                                <div
                                    class="p-3 bg-luxury-bg/40 border border-luxury-border/50 rounded-xl flex items-center justify-between text-xs transition-colors duration-300 hover:border-luxury-gold/20">
                                    <div>
                                        <p class="font-semibold text-white">{{ $order->order_number }}</p>
                                        <p class="text-luxury-secondary text-[10px] mt-0.5">{{ $order->user->full_name }} •
                                            {{ $order->order_date->locale('fr')->isoFormat('D MMM') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-luxury-gold">{{ number_format($order->total, 2) }} DH</p>
                                        <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[8px] font-display font-bold uppercase 
                                                @if($order->status->value === 'pending') bg-yellow-950/40 text-yellow-400 border border-yellow-800/30
                                                @elseif($order->status->value === 'completed' || $order->status->value === 'delivered') bg-green-950/40 text-green-400 border border-green-800/30
                                                @else bg-red-950/40 text-red-400 border border-red-900/30 @endif">
                                            {{ $order->status->label() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-luxury-secondary text-xs italic text-center py-8">Aucune commande trouvée.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert Panel -->
                @if($lowStockProducts->isNotEmpty())
                    <div class="bg-red-950/10 border border-red-900/30 rounded-2xl p-6 shadow-xl">
                        <h3
                            class="font-display font-bold text-base uppercase tracking-tight text-red-400 mb-4 flex items-center gap-2 pb-2 border-b border-red-900/20">
                            <span>⚠</span> Alerte stock faible
                        </h3>
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($lowStockProducts as $product)
                                <div
                                    class="p-3.5 bg-luxury-bg/50 border border-red-900/20 rounded-xl flex items-center justify-between text-xs">
                                    <div>
                                        <p class="font-semibold text-white">{{ $product->name }}</p>
                                        <p class="text-luxury-secondary text-[10px] mt-0.5">{{ $product->category->name }}</p>
                                    </div>
                                    <span
                                        class="bg-red-950 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold font-display border border-red-900/40">
                                        {{ $product->stock_quantity }} restant(s)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- TAB CONTENT: CATEGORIES -->
            <div x-show="activeTab === 'categories'" x-transition:enter="transition ease-out duration-300"
                class="grid gap-8 lg:grid-cols-12" style="display: none;">
                <!-- Add Category (Left) -->
                <div class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl h-fit">
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-col gap-4">
                        @csrf
                        <h2
                            class="font-display font-bold text-lg uppercase tracking-tight text-white flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                            <span class="text-luxury-gold">◆</span> Ajouter une catégorie
                        </h2>
                        <div class="space-y-3">
                            <input
                                class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40"
                                name="name" placeholder="Nom de la catégorie" required>
                            <textarea
                                class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40 h-32 resize-none"
                                name="description" placeholder="Description de la catégorie" required></textarea>
                        </div>
                        <button
                            class="w-full bg-luxury-gold hover:bg-white text-luxury-bg hover:text-luxury-bg py-3.5 rounded-xl text-xs font-display font-bold uppercase tracking-widest transition-all duration-300 shadow-md cursor-pointer">
                            Enregistrer la catégorie
                        </button>
                    </form>
                </div>

                <!-- Manage Categories (Right) -->
                <div
                    class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[550px]">
                    <h2
                        class="font-display font-bold text-lg uppercase tracking-tight text-white mb-6 flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                        <span class="text-luxury-gold">◆</span> Gérer les catégories
                    </h2>
                    <div class="overflow-y-auto grow pr-1 space-y-4 custom-scrollbar">
                        @forelse($categories as $category)
                            <div
                                class="p-4 bg-luxury-bg/50 border border-luxury-border/60 hover:border-luxury-gold/30 rounded-xl transition-all duration-300 space-y-3">
                                <form id="update-category-{{ $category->id }}" method="POST"
                                    action="{{ route('admin.categories.update', $category) }}" class="flex flex-col gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input
                                        class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-2 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300"
                                        name="name" value="{{ $category->name }}" placeholder="Nom">
                                    <textarea
                                        class="w-full bg-luxury-bg border border-luxury-border text-luxury-secondary px-3 py-2 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300 h-16 resize-none"
                                        name="description" placeholder="Description">{{ $category->description }}</textarea>
                                </form>
                                <div class="flex justify-between items-center pt-2 border-t border-luxury-border/40">
                                    <button type="submit" form="update-category-{{ $category->id }}"
                                        class="bg-luxury-gold/10 hover:bg-luxury-gold text-luxury-gold hover:text-luxury-bg px-3.5 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                        Mettre à jour
                                    </button>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                        class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="bg-red-950/20 hover:bg-red-900 border border-red-900/40 text-red-400 hover:text-white px-3.5 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-luxury-secondary text-xs italic text-center py-8">Aucune catégorie enregistrée.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: SERVICES -->
            <div x-show="activeTab === 'services'" x-transition:enter="transition ease-out duration-300"
                class="grid gap-8 lg:grid-cols-12" style="display: none;">
                <!-- Add Service (Left Column 4) -->
                <div
                    class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-6 sm:p-7 shadow-2xl h-fit">
                    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data"
                        class="flex flex-col gap-5">
                        @csrf
                        <div class="flex items-center gap-3 border-b border-luxury-border/40 pb-4">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Ajouter un service</h2>
                                <p class="text-xs text-luxury-secondary">Enrichissez la carte de votre salon</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Titre du service</label>
                                <input
                                    class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs font-medium transition-all duration-300 placeholder-luxury-secondary/40"
                                    name="name" placeholder="ex. Coupe Prestige & Taille de Barbe" required>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex flex-col gap-1.5">
                                    <label
                                        class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Prix (DH)</label>
                                    <input type="number" step="0.01"
                                        class="w-full bg-luxury-bg/50 border border-luxury-border text-luxury-gold font-bold px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 placeholder-luxury-secondary/40"
                                        name="price" placeholder="30.00" required>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label
                                        class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Durée (Min)</label>
                                    <input type="number"
                                        class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 placeholder-luxury-secondary/40"
                                        name="duration" placeholder="45" required>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Description</label>
                                <textarea
                                    class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 placeholder-luxury-secondary/40 h-24 resize-none leading-relaxed"
                                    name="description"
                                    placeholder="Description détaillée du soin et de ses bienfaits..."
                                    required></textarea>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Image du service</label>
                                <div
                                    class="relative flex items-center justify-center rounded-xl border border-dashed border-luxury-border bg-luxury-bg/30 p-4 transition hover:border-luxury-gold/50">
                                    <input type="file" name="image" id="new-service-image"
                                        class="absolute inset-0 size-full opacity-0 cursor-pointer">
                                    <div class="flex flex-col items-center gap-1.5 text-center">
                                        <svg class="size-6 text-luxury-gold" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs font-bold text-white">Cliquez ou importez une photo du service</span>
                                        <span class="text-[10px] text-luxury-secondary">PNG, JPG, WEBP jusqu'à 2 Mo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-luxury-gold hover:bg-white text-black py-3.5 rounded-xl text-xs font-display font-bold uppercase tracking-widest transition-all duration-300 shadow-md cursor-pointer text-center">
                            + Enregistrer le service
                        </button>
                    </form>
                </div>

                <!-- Manage Services (Right Column 8) -->
                <div
                    class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-6 sm:p-7 shadow-2xl flex flex-col min-h-[640px]">
                    <div class="flex items-center justify-between border-b border-luxury-border/40 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Gérer les services au menu</h2>
                                <p class="text-xs text-luxury-secondary">Mettre à jour les titres, prix, durées et photos</p>
                            </div>
                        </div>
                        <span
                            class="rounded-full border border-luxury-border bg-luxury-bg/50 px-3 py-1 text-xs font-bold text-luxury-gold">
                            {{ $services->count() }} Services
                        </span>
                    </div>

                    <div class="overflow-y-auto grow pr-1 space-y-4 custom-scrollbar">
                        @forelse($services as $service)
                            @php
                                $srvImg = null;
                                if ($service->image_path) {
                                    if (str_starts_with($service->image_path, 'http://') || str_starts_with($service->image_path, 'https://')) {
                                        $srvImg = $service->image_path;
                                    } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($service->image_path)) {
                                        $srvImg = asset('storage/' . $service->image_path);
                                    }
                                }
                                if (!$srvImg) {
                                    $srvImg = 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=600&q=80';
                                    $lowerName = strtolower($service->name);
                                    if (str_contains($lowerName, 'haircut') || str_contains($lowerName, 'hair')) {
                                        $srvImg = 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains($lowerName, 'beard') || str_contains($lowerName, 'trim')) {
                                        $srvImg = 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=600&q=80';
                                    } elseif (str_contains($lowerName, 'shave') || str_contains($lowerName, 'facial')) {
                                        $srvImg = 'https://images.unsplash.com/photo-1517832606589-7a598bb03b15?auto=format&fit=crop&w=600&q=80';
                                    }
                                }
                            @endphp

                            <div
                                class="group relative rounded-2xl border border-luxury-border/60 bg-luxury-bg/40 p-4 sm:p-5 shadow-lg transition-all duration-300 hover:border-luxury-gold/40">
                                <form id="update-service-{{ $service->id }}" method="POST"
                                    action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data"
                                    class="flex flex-col sm:flex-row gap-5">
                                    @csrf
                                    @method('PUT')

                                    <!-- Service Image Preview & Change Overlay -->
                                    <div
                                        class="relative size-28 sm:size-32 shrink-0 overflow-hidden rounded-xl bg-black/40 border border-luxury-border/60 group-hover:border-luxury-gold/40 transition-colors">
                                        <img src="{{ $srvImg }}" alt="{{ $service->name }}"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

                                        <!-- Status Badge Overlay -->
                                        <span
                                            class="absolute top-2 left-2 rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider shadow-md backdrop-blur-md {{ $service->is_active ? 'border-emerald-500/30 bg-emerald-500/20 text-emerald-300' : 'border-rose-500/30 bg-rose-500/20 text-rose-300' }}">
                                            {{ $service->is_active ? 'Actif' : 'Inactif' }}
                                        </span>

                                        <!-- Change Photo File Input Overlay -->
                                        <label
                                            class="absolute inset-0 flex flex-col items-center justify-center bg-black/65 opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer text-white text-[10px] font-bold uppercase tracking-wider text-center p-1">
                                            <svg class="size-5 mb-1 text-luxury-gold" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            </svg>
                                            Changer la photo
                                            <input type="file" name="image" class="hidden"
                                                onchange="document.getElementById('update-service-{{ $service->id }}').submit()">
                                        </label>
                                    </div>

                                    <!-- Service Details Form Fields -->
                                    <div class="flex flex-col grow gap-3">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="grow">
                                                <label
                                                    class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Titre du service</label>
                                                <input type="text" name="name" value="{{ $service->name }}" required
                                                    class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 text-xs font-bold text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <div class="w-24">
                                                    <label
                                                        class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Prix (DH)</label>
                                                    <input type="number" step="0.01" name="price" value="{{ $service->price }}"
                                                        required
                                                        class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3 py-2 text-xs font-bold text-luxury-gold placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                                </div>
                                                <div class="w-24">
                                                    <label
                                                        class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Durée (Min)</label>
                                                    <input type="number" name="duration" value="{{ $service->duration }}"
                                                        required
                                                        class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3 py-2 text-xs font-bold text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Description</label>
                                            <textarea name="description" rows="2"
                                                class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300 resize-none leading-relaxed">{{ $service->description }}</textarea>
                                        </div>

                                        <!-- Card Actions -->
                                        <div
                                            class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-luxury-border/40 mt-1">
                                            <div class="flex items-center gap-2">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-xl bg-luxury-gold px-4 py-2 font-display text-[10px] font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white cursor-pointer shadow-md">
                                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Enregistrer les modifications
                                                </button>
                                                <a href="{{ route('admin.services.appointments', $service) }}"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-primary transition-all duration-300 hover:border-luxury-gold hover:text-luxury-gold">
                                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    Réservations ({{ $service->appointments()->count() }})
                                                </a>
                                            </div>

                                            <form method="POST" action="{{ route('admin.services.toggle-status', $service) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 font-display text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer {{ $service->is_active ? 'border-rose-500/40 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white' : 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500 hover:text-black' }}">
                                                    {{ $service->is_active ? 'Désactiver le service' : 'Activer le service' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <div class="py-12 text-center flex flex-col items-center gap-2">
                                <span class="text-xs text-luxury-secondary italic">Aucun service enregistré pour le moment.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: PRODUCTS -->
            <div x-show="activeTab === 'products'" x-transition:enter="transition ease-out duration-300"
                class="grid gap-8 lg:grid-cols-12" style="display: none;">
                <!-- Add Product (Left Column 4) -->
                <div
                    class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-6 sm:p-7 shadow-2xl h-fit">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                        class="flex flex-col gap-5">
                        @csrf
                        <div class="flex items-center gap-3 border-b border-luxury-border/40 pb-4">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Ajouter un produit</h2>
                                <p class="text-xs text-luxury-secondary">Agrandissez votre inventaire boutique</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Catégorie du produit</label>
                                <select
                                    class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 font-body"
                                    name="category_id" required>
                                    <option value="" disabled selected>Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="bg-luxury-surface text-white">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Titre du produit</label>
                                <input
                                    class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs font-medium transition-all duration-300 placeholder-luxury-secondary/40"
                                    name="name" placeholder="ex. Pommade Coiffante Effet Mat" required>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex flex-col gap-1.5">
                                    <label
                                        class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Prix (DH)</label>
                                    <input type="number" step="0.01"
                                        class="w-full bg-luxury-bg/50 border border-luxury-border text-luxury-gold font-bold px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 placeholder-luxury-secondary/40"
                                        name="price" placeholder="28.00" required>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label
                                        class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Quantité en stock</label>
                                    <input type="number"
                                        class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 placeholder-luxury-secondary/40"
                                        name="stock_quantity" placeholder="50" required>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Description</label>
                                <textarea
                                    class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold focus:ring-1 focus:ring-luxury-gold outline-none text-xs transition-all duration-300 placeholder-luxury-secondary/40 h-24 resize-none leading-relaxed"
                                    name="description"
                                    placeholder="Description détaillée du produit, ingrédients et conseils d'utilisation..."
                                    required></textarea>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-luxury-secondary">Image du produit</label>
                                <div
                                    class="relative flex items-center justify-center rounded-xl border border-dashed border-luxury-border bg-luxury-bg/30 p-4 transition hover:border-luxury-gold/50">
                                    <input type="file" name="image" id="new-product-image"
                                        class="absolute inset-0 size-full opacity-0 cursor-pointer">
                                    <div class="flex flex-col items-center gap-1.5 text-center">
                                        <svg class="size-6 text-luxury-gold" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs font-bold text-white">Cliquez ou importez une photo du produit</span>
                                        <span class="text-[10px] text-luxury-secondary">PNG, JPG, WEBP jusqu'à 2 Mo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-luxury-gold hover:bg-white text-black py-3.5 rounded-xl text-xs font-display font-bold uppercase tracking-widest transition-all duration-300 shadow-md cursor-pointer text-center">
                            + Enregistrer le produit
                        </button>
                    </form>
                </div>

                <!-- Manage Products (Right Column 8) -->
                <div
                    class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-3xl p-6 sm:p-7 shadow-2xl flex flex-col min-h-[640px]">
                    <div class="flex items-center justify-between border-b border-luxury-border/40 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="grid size-9 place-items-center rounded-xl bg-luxury-gold/10 text-luxury-gold">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-base font-bold text-white">Gérer l'inventaire des produits</h2>
                                <p class="text-xs text-luxury-secondary">Mettre à jour titres, prix, stocks et photos</p>
                            </div>
                        </div>
                        <span
                            class="rounded-full border border-luxury-border bg-luxury-bg/50 px-3 py-1 text-xs font-bold text-luxury-gold">
                            {{ $products->count() }} Produits
                        </span>
                    </div>

                    <div class="overflow-y-auto grow pr-1 space-y-4 custom-scrollbar">
                        @forelse($products as $product)
                            @php
                                $imgUrl = null;
                                if ($product->image_path) {
                                    if (str_starts_with($product->image_path, 'http://') || str_starts_with($product->image_path, 'https://')) {
                                        $imgUrl = $product->image_path;
                                    } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_path)) {
                                        $imgUrl = asset('storage/' . $product->image_path);
                                    }
                                }
                                if (!$imgUrl) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=600&q=80';
                                }
                            @endphp

                            <div
                                class="group relative rounded-2xl border border-luxury-border/60 bg-luxury-bg/40 p-4 sm:p-5 shadow-lg transition-all duration-300 hover:border-luxury-gold/40">
                                <form id="update-product-{{ $product->id }}" method="POST"
                                    action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
                                    class="flex flex-col sm:flex-row gap-5">
                                    @csrf
                                    @method('PUT')

                                    <!-- Product Image Preview & Change Overlay -->
                                    <div
                                        class="relative size-28 sm:size-32 shrink-0 overflow-hidden rounded-xl bg-black/40 border border-luxury-border/60 group-hover:border-luxury-gold/40 transition-colors">
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

                                        <!-- Stock Status Badge Overlay -->
                                        <span
                                            class="absolute top-2 left-2 rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider shadow-md backdrop-blur-md {{ $product->stock_quantity > 0 ? 'border-emerald-500/30 bg-emerald-500/20 text-emerald-300' : 'border-rose-500/30 bg-rose-500/20 text-rose-300' }}">
                                            {{ $product->stock_quantity > 0 ? 'En stock (' . $product->stock_quantity . ')' : 'Rupture de stock' }}
                                        </span>

                                        <!-- Change Photo File Input Overlay -->
                                        <label
                                            class="absolute inset-0 flex flex-col items-center justify-center bg-black/65 opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer text-white text-[10px] font-bold uppercase tracking-wider text-center p-1">
                                            <svg class="size-5 mb-1 text-luxury-gold" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            </svg>
                                            Changer la photo
                                            <input type="file" name="image" class="hidden"
                                                onchange="document.getElementById('update-product-{{ $product->id }}').submit()">
                                        </label>
                                    </div>

                                    <!-- Product Details Form Fields -->
                                    <div class="flex flex-col grow gap-3">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="grow">
                                                <label
                                                    class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Titre du produit</label>
                                                <input type="text" name="name" value="{{ $product->name }}" required
                                                    class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 text-xs font-bold text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <div class="w-24">
                                                    <label
                                                        class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Prix (DH)</label>
                                                    <input type="number" step="0.01" name="price" value="{{ $product->price }}"
                                                        required
                                                        class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3 py-2 text-xs font-bold text-luxury-gold placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                                </div>
                                                <div class="w-24">
                                                    <label
                                                        class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Stock</label>
                                                    <input type="number" name="stock_quantity"
                                                        value="{{ $product->stock_quantity }}" required
                                                        class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3 py-2 text-xs font-bold text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                            <div class="sm:col-span-5">
                                                <label
                                                    class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Catégorie</label>
                                                <select name="category_id"
                                                    class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 text-xs font-bold text-white focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            @selected($category->is($product->category))
                                                            class="bg-luxury-surface text-white">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="sm:col-span-7">
                                                <label
                                                    class="text-[9px] font-bold uppercase tracking-wider text-luxury-secondary block mb-1">Description</label>
                                                <input type="text" name="description" value="{{ $product->description }}"
                                                    class="w-full rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 text-xs text-white placeholder-luxury-secondary/50 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold transition-all duration-300">
                                            </div>
                                        </div>

                                        <!-- Card Actions -->
                                        <div
                                            class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-luxury-border/40 mt-1">
                                            <div class="flex items-center gap-2">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-xl bg-luxury-gold px-4 py-2 font-display text-[10px] font-bold uppercase tracking-widest text-black transition-all duration-300 hover:bg-white cursor-pointer shadow-md">
                                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Enregistrer les modifications
                                                </button>
                                                <a href="{{ route('products.show', $product) }}" target="_blank"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border border-luxury-border bg-luxury-surface px-3.5 py-2 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-primary transition-all duration-300 hover:border-luxury-gold hover:text-luxury-gold">
                                                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    Voir la page du produit
                                                </a>
                                            </div>

                                            <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 font-display text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer {{ $product->is_active ? 'border-rose-500/40 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white' : 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500 hover:text-black' }}">
                                                    {{ $product->is_active ? 'Désactiver le produit' : 'Activer le produit' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <div class="py-12 text-center flex flex-col items-center gap-2">
                                <span class="text-xs text-luxury-secondary italic">Aucun produit enregistré pour le moment.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for lists */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--color-luxury-border, #27272A);
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--color-luxury-gold, #C8A46A);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let revenueChartInstance = null;

        const chartDataSets = {
            week: {
                labels: @json($chartWeekLabels),
                data: @json($chartWeekData),
                subtitle: 'Daily sales performance for this week'
            },
            month: {
                labels: @json($chartMonthLabels),
                data: @json($chartMonthData),
                subtitle: 'Daily sales performance for {{ now()->format("F Y") }}'
            },
            year: {
                labels: @json($chartYearLabels),
                data: @json($chartYearData),
                subtitle: 'Monthly sales performance for {{ now()->year }}'
            }
        };

        window.updateRevenueChart = function(timeframe) {
            if (!revenueChartInstance) return;
            const ds = chartDataSets[timeframe];
            if (!ds) return;

            revenueChartInstance.data.labels = ds.labels;
            revenueChartInstance.data.datasets[0].data = ds.data;
            revenueChartInstance.update();

            const subtitleEl = document.getElementById('revenueChartSubtitle');
            if (subtitleEl) subtitleEl.textContent = ds.subtitle;
        };

        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94A3B8' : '#475569';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.06)';

            // 1. Revenue Line/Area Chart
            const revCtx = document.getElementById('revenueChart');
            if (revCtx) {
                const revGradient = revCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                revGradient.addColorStop(0, 'rgba(200, 164, 106, 0.35)');
                revGradient.addColorStop(1, 'rgba(200, 164, 106, 0.0)');

                revenueChartInstance = new Chart(revCtx, {
                    type: 'line',
                    data: {
                        labels: chartDataSets.year.labels,
                        datasets: [{
                            label: 'Revenue (DH)',
                            data: chartDataSets.year.data,
                            borderColor: '#C8A46A',
                            borderWidth: 3,
                            backgroundColor: revGradient,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#C8A46A',
                            pointBorderColor: '#FFFFFF',
                            pointHoverRadius: 6,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y.toLocaleString() + ' DH';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: gridColor },
                                ticks: { color: textColor, font: { family: 'Plus Jakarta Sans', size: 11 } }
                            },
                            y: {
                                grid: { color: gridColor },
                                ticks: {
                                    color: textColor,
                                    font: { family: 'Plus Jakarta Sans', size: 11 },
                                    callback: function(value) { return value + ' DH'; }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Appointments Status Doughnut Chart
            const appCtx = document.getElementById('appointmentsChart');
            if (appCtx) {
                const appData = @json(array_values($appointmentStatusesChart));
                const appLabels = @json(array_keys($appointmentStatusesChart));

                new Chart(appCtx, {
                    type: 'doughnut',
                    data: {
                        labels: appLabels,
                        datasets: [{
                            data: appData,
                            backgroundColor: [
                                '#10B981', // Confirmed (Emerald)
                                '#F59E0B', // Pending (Amber)
                                '#3B82F6', // Completed (Blue)
                                '#EF4444'  // Cancelled (Red)
                            ],
                            borderWidth: 2,
                            borderColor: isDark ? '#111111' : '#FFFFFF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: textColor,
                                    font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                                    padding: 15,
                                    usePointStyle: true
                                }
                            }
                        },
                        cutout: '68%'
                    }
                });
            }
        });
    </script>
@endsection