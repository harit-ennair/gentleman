@extends('layouts.test')

@section('content')
<div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ activeTab: 'overview' }">
    <!-- Left Sidebar Column (3 cols) -->
    <div class="lg:col-span-3">
        <div class="bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 lg:sticky lg:top-28 space-y-6">
            <!-- Sidebar Header -->
            <div>
                <span class="text-luxury-gold uppercase tracking-[0.2em] text-[10px] font-display block mb-1">Navigation</span>
                <h3 class="font-display font-black text-lg uppercase tracking-tight text-white flex items-center gap-2">
                    <span class="text-luxury-gold">◆</span> Admin Menu
                </h3>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-2">
                <!-- Overview Tab Link -->
                <button @click="activeTab = 'overview'" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'overview' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                    <span class="text-sm">📊</span> Overview
                </button>

                <!-- Categories Tab Link -->
                <button @click="activeTab = 'categories'" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'categories' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                    <span class="text-sm">📁</span> Categories
                </button>

                <!-- Services Tab Link -->
                <button @click="activeTab = 'services'" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'services' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                    <span class="text-sm">💇</span> Services
                </button>

                <!-- Products Tab Link -->
                <button @click="activeTab = 'products'" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border text-xs font-display font-bold uppercase tracking-wider text-left transition-all duration-300 cursor-pointer"
                        :class="activeTab === 'products' ? 'bg-luxury-bg border-luxury-gold text-luxury-gold shadow-lg shadow-luxury-gold/5' : 'bg-transparent border-transparent text-luxury-secondary hover:text-white hover:bg-luxury-bg/30'">
                    <span class="text-sm">🧴</span> Products
                </button>
            </nav>

            <!-- Separator -->
            <div class="h-px bg-luxury-border/60"></div>

            <!-- External Pages Links -->
            <div class="space-y-3">
                <span class="text-luxury-secondary/50 uppercase tracking-[0.2em] text-[9px] font-display block px-1">Manage Resources</span>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-display font-semibold uppercase tracking-wider text-luxury-secondary hover:text-luxury-gold hover:bg-luxury-bg/30 transition-all duration-300">
                        <span class="text-sm">👥</span> Users
                    </a>
                    <a href="{{ route('admin.appointments.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-display font-semibold uppercase tracking-wider text-luxury-secondary hover:text-luxury-gold hover:bg-luxury-bg/30 transition-all duration-300">
                        <span class="text-sm">📅</span> Appointments
                    </a>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-display font-semibold uppercase tracking-wider text-luxury-secondary hover:text-luxury-gold hover:bg-luxury-bg/30 transition-all duration-300">
                        <span class="text-sm">📦</span> Orders
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
                <span class="text-luxury-gold uppercase tracking-[0.2em] text-xs font-display mb-2 block">Control Panel</span>
                <h1 class="font-display font-black text-4xl md:text-5xl uppercase tracking-tighter text-white">
                    <span x-show="activeTab === 'overview'">Dashboard Overview</span>
                    <span x-show="activeTab === 'categories'" style="display: none;">Categories Manager</span>
                    <span x-show="activeTab === 'services'" style="display: none;">Services Manager</span>
                    <span x-show="activeTab === 'products'" style="display: none;">Products Manager</span>
                </h1>
            </div>
            <!-- Quick Actions -->
            <div class="flex items-center gap-2 text-xs">
                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-luxury-secondary font-display uppercase tracking-widest text-[10px]">Admin Active</span>
            </div>
        </div>

        <!-- TAB CONTENT: OVERVIEW -->
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" class="w-full space-y-8">
            <!-- Stats Cards Grid -->
            <div class="w-full grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                @foreach([
                    'Clients' => ['value' => $clientsCount],
                    'Appointments' => ['value' => $appointmentsCount],
                    'Orders' => ['value' => $ordersCount],
                    'Today Revenue' => ['value' => number_format($todayRevenue, 0) . ' DH'],
                    'Month Revenue' => ['value' => number_format($monthlyRevenue, 0) . ' DH']
                ] as $label => $data)
                    <div class="group bg-luxury-surface border border-luxury-border/60 hover:border-luxury-gold/40 rounded-2xl p-5 transition-all duration-500 shadow-xl flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-luxury-secondary text-[10px] uppercase tracking-wider font-display">{{ $label }}</span>
                        </div>
                        <p class="text-2xl font-display font-bold text-white tracking-tight group-hover:text-luxury-gold transition-colors duration-300">
                            {{ $data['value'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Recent Activity Sections -->
            <div class="w-full grid gap-8 grid-cols-1 lg:grid-cols-2">
                <!-- Recent Appointments -->
                <div class="bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[380px]">
                    <h3 class="font-display font-bold text-base uppercase tracking-tight text-white mb-4 flex items-center gap-2 pb-2 border-b border-luxury-border/40">
                        <span class="text-luxury-gold">◆</span> Recent Appointments
                    </h3>
                    <div class="overflow-y-auto grow custom-scrollbar space-y-3 pr-1">
                        @forelse($latestAppointments as $appointment)
                            <div class="p-3 bg-luxury-bg/40 border border-luxury-border/50 rounded-xl flex items-center justify-between text-xs transition-colors duration-300 hover:border-luxury-gold/20">
                                <div>
                                    <p class="font-semibold text-white">{{ $appointment->user->full_name }}</p>
                                    <p class="text-luxury-secondary text-[10px] mt-0.5">{{ $appointment->service->name }} • {{ $appointment->appointment_at->format('M d, H:i') }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[8px] font-display font-bold uppercase 
                                    @if($appointment->status->value === 'pending') bg-yellow-950/40 text-yellow-400 border border-yellow-800/30
                                    @elseif($appointment->status->value === 'confirmed') bg-green-950/40 text-green-400 border border-green-800/30
                                    @elseif($appointment->status->value === 'completed') bg-blue-950/40 text-blue-400 border border-blue-800/30
                                    @else bg-red-950/40 text-red-400 border border-red-900/30 @endif">
                                    {{ $appointment->status->value }}
                                </span>
                            </div>
                        @empty
                            <p class="text-luxury-secondary text-xs italic text-center py-8">No appointments found.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[380px]">
                    <h3 class="font-display font-bold text-base uppercase tracking-tight text-white mb-4 flex items-center gap-2 pb-2 border-b border-luxury-border/40">
                        <span class="text-luxury-gold">◆</span> Recent Orders
                    </h3>
                    <div class="overflow-y-auto grow custom-scrollbar space-y-3 pr-1">
                        @forelse($latestOrders as $order)
                            <div class="p-3 bg-luxury-bg/40 border border-luxury-border/50 rounded-xl flex items-center justify-between text-xs transition-colors duration-300 hover:border-luxury-gold/20">
                                <div>
                                    <p class="font-semibold text-white">{{ $order->order_number }}</p>
                                    <p class="text-luxury-secondary text-[10px] mt-0.5">{{ $order->user->full_name }} • {{ $order->order_date->format('M d') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-luxury-gold">{{ number_format($order->total, 2) }} DH</p>
                                    <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[8px] font-display font-bold uppercase 
                                        @if($order->status->value === 'pending') bg-yellow-950/40 text-yellow-400 border border-yellow-800/30
                                        @elseif($order->status->value === 'completed' || $order->status->value === 'delivered') bg-green-950/40 text-green-400 border border-green-800/30
                                        @else bg-red-950/40 text-red-400 border border-red-900/30 @endif">
                                        {{ $order->status->value }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-luxury-secondary text-xs italic text-center py-8">No orders found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert Panel -->
            @if($lowStockProducts->isNotEmpty())
                <div class="bg-red-950/10 border border-red-900/30 rounded-2xl p-6 shadow-xl">
                    <h3 class="font-display font-bold text-base uppercase tracking-tight text-red-400 mb-4 flex items-center gap-2 pb-2 border-b border-red-900/20">
                        <span>⚠</span> Low Stock Alert
                    </h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($lowStockProducts as $product)
                            <div class="p-3.5 bg-luxury-bg/50 border border-red-900/20 rounded-xl flex items-center justify-between text-xs">
                                <div>
                                    <p class="font-semibold text-white">{{ $product->name }}</p>
                                    <p class="text-luxury-secondary text-[10px] mt-0.5">{{ $product->category->name }}</p>
                                </div>
                                <span class="bg-red-950 text-red-400 px-2.5 py-1 rounded-lg text-[10px] font-bold font-display border border-red-900/40">
                                    {{ $product->stock_quantity }} Left
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- TAB CONTENT: CATEGORIES -->
        <div x-show="activeTab === 'categories'" x-transition:enter="transition ease-out duration-300" class="grid gap-8 lg:grid-cols-12" style="display: none;">
            <!-- Add Category (Left) -->
            <div class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl h-fit">
                <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-col gap-4">
                    @csrf
                    <h2 class="font-display font-bold text-lg uppercase tracking-tight text-white flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                        <span class="text-luxury-gold">◆</span> Add Category
                    </h2>
                    <div class="space-y-3">
                        <input class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                               name="name" 
                               placeholder="Category Name" 
                               required>
                        <textarea class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40 h-32 resize-none" 
                                  name="description" 
                                  placeholder="Category Description" 
                                  required></textarea>
                    </div>
                    <button class="w-full bg-luxury-gold hover:bg-white text-luxury-bg hover:text-luxury-bg py-3.5 rounded-xl text-xs font-display font-bold uppercase tracking-widest transition-all duration-300 shadow-md cursor-pointer">
                        Save Category
                    </button>
                </form>
            </div>

            <!-- Manage Categories (Right) -->
            <div class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[550px]">
                <h2 class="font-display font-bold text-lg uppercase tracking-tight text-white mb-6 flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                    <span class="text-luxury-gold">◆</span> Manage Categories
                </h2>
                <div class="overflow-y-auto grow pr-1 space-y-4 custom-scrollbar">
                    @forelse($categories as $category)
                        <div class="p-4 bg-luxury-bg/50 border border-luxury-border/60 hover:border-luxury-gold/30 rounded-xl transition-all duration-300 space-y-3">
                            <form id="update-category-{{ $category->id }}" method="POST" action="{{ route('admin.categories.update',$category) }}" class="flex flex-col gap-2">
                                @csrf 
                                @method('PUT')
                                <input class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-2 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                       name="name" 
                                       value="{{ $category->name }}" 
                                       placeholder="Name">
                                <textarea class="w-full bg-luxury-bg border border-luxury-border text-luxury-secondary px-3 py-2 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300 h-16 resize-none" 
                                          name="description" 
                                          placeholder="Description">{{ $category->description }}</textarea>
                            </form>
                            <div class="flex justify-between items-center pt-2 border-t border-luxury-border/40">
                                <button type="submit" form="update-category-{{ $category->id }}" class="bg-luxury-gold/10 hover:bg-luxury-gold text-luxury-gold hover:text-luxury-bg px-3.5 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                    Update
                                </button>
                                <form method="POST" action="{{ route('admin.categories.destroy',$category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="bg-red-950/20 hover:bg-red-900 border border-red-900/40 text-red-400 hover:text-white px-3.5 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-luxury-secondary text-xs italic text-center py-8">No categories registered.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: SERVICES -->
        <div x-show="activeTab === 'services'" x-transition:enter="transition ease-out duration-300" class="grid gap-8 lg:grid-cols-12" style="display: none;">
            <!-- Add Service (Left) -->
            <div class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl h-fit">
                <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <h2 class="font-display font-bold text-lg uppercase tracking-tight text-white flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                        <span class="text-luxury-gold">◆</span> Add Service
                    </h2>
                    <div class="space-y-3">
                        <input class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                               name="name" 
                               placeholder="Service Name" 
                               required>
                        <div class="flex gap-3">
                            <input class="w-1/2 bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                                   name="price" 
                                   placeholder="Price ($)" 
                                   required>
                            <input class="w-1/2 bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                                   name="duration" 
                                   placeholder="Duration (Min)" 
                                   required>
                        </div>
                        <textarea class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40 h-24 resize-none" 
                                  name="description" 
                                  placeholder="Service Description" 
                                  required></textarea>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-luxury-secondary/80 font-display">Service Image</label>
                            <input type="file" 
                                   name="image" 
                                   class="block w-full text-xs text-luxury-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-luxury-gold/10 file:text-luxury-gold hover:file:bg-luxury-gold/20 file:cursor-pointer">
                        </div>
                    </div>
                    <button class="w-full bg-luxury-gold hover:bg-white text-luxury-bg hover:text-luxury-bg py-3.5 rounded-xl text-xs font-display font-bold uppercase tracking-widest transition-all duration-300 shadow-md cursor-pointer">
                        Save Service
                    </button>
                </form>
            </div>

            <!-- Manage Services (Right) -->
            <div class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[580px]">
                <h2 class="font-display font-bold text-lg uppercase tracking-tight text-white mb-6 flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                    <span class="text-luxury-gold">◆</span> Manage Services
                </h2>
                <div class="overflow-y-auto grow pr-1 space-y-4 custom-scrollbar">
                    @forelse($services as $service)
                        <div class="p-4 bg-luxury-bg/50 border border-luxury-border/60 hover:border-luxury-gold/30 rounded-xl transition-all duration-300 space-y-3">
                            <form id="update-service-{{ $service->id }}" method="POST" action="{{ route('admin.services.update',$service) }}" class="flex flex-col gap-2">
                                @csrf 
                                @method('PUT')
                                <div class="flex gap-2 items-center justify-between">
                                    <input class="grow bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                           name="name" 
                                           value="{{ $service->name }}">
                                    <span class="px-2 py-0.5 rounded text-[8px] font-display font-bold uppercase {{ $service->is_active ? 'bg-green-950 text-green-400 border border-green-800/40' : 'bg-red-950 text-red-400 border border-red-900/40' }}">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <div class="w-1/2">
                                        <label class="text-[8px] uppercase tracking-wider text-luxury-secondary/80 font-display block mb-1">Price ($)</label>
                                        <input class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                               name="price" 
                                               value="{{ $service->price }}">
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-[8px] uppercase tracking-wider text-luxury-secondary/80 font-display block mb-1">Duration (Min)</label>
                                        <input class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                               name="duration" 
                                               value="{{ $service->duration }}">
                                    </div>
                                </div>
                                <input type="hidden" name="description" value="{{ $service->description }}">
                            </form>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-luxury-border/40">
                                <div class="flex gap-1.5">
                                    <button type="submit" form="update-service-{{ $service->id }}" class="bg-luxury-gold/10 hover:bg-luxury-gold text-luxury-gold hover:text-luxury-bg px-3 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.services.appointments',$service) }}" 
                                       class="bg-luxury-surface hover:bg-luxury-border border border-luxury-border px-3 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider text-luxury-primary transition-all duration-300">
                                        Bookings
                                    </a>
                                </div>
                                <form method="POST" action="{{ route('admin.services.toggle-status',$service) }}" class="inline">
                                    @csrf
                                    <button class="bg-luxury-surface hover:bg-luxury-border border border-luxury-border text-luxury-secondary hover:text-luxury-gold px-3 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                        Toggle {{ $service->is_active ? 'Off' : 'On' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-luxury-secondary text-xs italic text-center py-8">No services registered.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: PRODUCTS -->
        <div x-show="activeTab === 'products'" x-transition:enter="transition ease-out duration-300" class="grid gap-8 lg:grid-cols-12" style="display: none;">
            <!-- Add Product (Left) -->
            <div class="lg:col-span-4 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl h-fit">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <h2 class="font-display font-bold text-lg uppercase tracking-tight text-white flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                        <span class="text-luxury-gold">◆</span> Add Product
                    </h2>
                    <div class="space-y-3">
                        <select class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300" 
                                name="category_id" 
                                required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" class="bg-luxury-surface text-white">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <input class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                               name="name" 
                               placeholder="Product Name" 
                               required>
                        <div class="flex gap-3">
                            <input class="w-1/2 bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                                   name="price" 
                                   placeholder="Price ($)" 
                                   required>
                            <input class="w-1/2 bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40" 
                                   name="stock_quantity" 
                                   placeholder="Stock" 
                                   required>
                        </div>
                        <textarea class="w-full bg-luxury-bg/50 border border-luxury-border text-white px-4 py-3 rounded-xl focus:border-luxury-gold/60 focus:ring-1 focus:ring-luxury-gold/60 outline-none text-sm transition-all duration-300 placeholder-luxury-secondary/40 h-20 resize-none" 
                                  name="description" 
                                  placeholder="Product Description" 
                                  required></textarea>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-luxury-secondary/80 font-display">Product Image</label>
                            <input type="file" 
                                   name="image" 
                                   class="block w-full text-xs text-luxury-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-luxury-gold/10 file:text-luxury-gold hover:file:bg-luxury-gold/20 file:cursor-pointer">
                        </div>
                    </div>
                    <button class="w-full bg-luxury-gold hover:bg-white text-luxury-bg hover:text-luxury-bg py-3.5 rounded-xl text-xs font-display font-bold uppercase tracking-widest transition-all duration-300 shadow-md cursor-pointer">
                        Save Product
                    </button>
                </form>
            </div>

            <!-- Manage Products (Right) -->
            <div class="lg:col-span-8 bg-luxury-surface border border-luxury-border/60 rounded-2xl p-6 shadow-xl flex flex-col h-[580px]">
                <h2 class="font-display font-bold text-lg uppercase tracking-tight text-white mb-6 flex items-center gap-2 pb-3 border-b border-luxury-border/40">
                    <span class="text-luxury-gold">◆</span> Manage Products
                </h2>
                <div class="overflow-y-auto grow pr-1 space-y-4 custom-scrollbar">
                    @forelse($products as $product)
                        <div class="p-4 bg-luxury-bg/50 border border-luxury-border/60 hover:border-luxury-gold/30 rounded-xl transition-all duration-300 space-y-3">
                            <form id="update-product-{{ $product->id }}" method="POST" action="{{ route('admin.products.update',$product) }}" class="flex flex-col gap-2">
                                @csrf 
                                @method('PUT')
                                <div class="flex gap-2 items-center justify-between">
                                    <input class="grow bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                           name="name" 
                                           value="{{ $product->name }}">
                                    <span class="px-2 py-0.5 rounded text-[8px] font-display font-bold uppercase {{ $product->is_active ? 'bg-green-950 text-green-400 border border-green-800/40' : 'bg-red-950 text-red-400 border border-red-900/40' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <div class="w-1/2">
                                        <label class="text-[8px] uppercase tracking-wider text-luxury-secondary/80 font-display block mb-1">Price ($)</label>
                                        <input class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                               name="price" 
                                               value="{{ $product->price }}">
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-[8px] uppercase tracking-wider text-luxury-secondary/80 font-display block mb-1">Stock</label>
                                        <input class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300" 
                                               name="stock_quantity" 
                                               value="{{ $product->stock_quantity }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[8px] uppercase tracking-wider text-luxury-secondary/80 font-display block mb-1">Category</label>
                                    <select name="category_id" class="w-full bg-luxury-bg border border-luxury-border text-white px-3 py-1.5 rounded-lg focus:border-luxury-gold/60 outline-none text-xs transition-all duration-300 font-body">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected($category->is($product->category)) class="bg-luxury-surface text-white">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="description" value="{{ $product->description }}">
                            </form>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-luxury-border/40">
                                <button type="submit" form="update-product-{{ $product->id }}" class="bg-luxury-gold/10 hover:bg-luxury-gold text-luxury-gold hover:text-luxury-bg px-3.5 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                    Update
                                </button>
                                <form method="POST" action="{{ route('admin.products.toggle-status',$product) }}" class="inline">
                                    @csrf
                                    <button class="bg-luxury-surface hover:bg-luxury-border border border-luxury-border text-luxury-secondary hover:text-luxury-gold px-3.5 py-1.5 rounded-lg text-[10px] font-display font-bold uppercase tracking-wider transition-all duration-300 cursor-pointer">
                                        Toggle {{ $product->is_active ? 'Off' : 'On' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-luxury-secondary text-xs italic text-center py-8">No products registered.</p>
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
@endsection


