@extends('layouts.test')

@section('title', 'Prendre rendez-vous')

@section('content')
    <div x-data="bookingWizard({{ Js::from($preselectedService ? ['id' => $preselectedService->id, 'name' => $preselectedService->name, 'description' => $preselectedService->description, 'price' => (float) $preselectedService->price, 'duration' => $preselectedService->duration] : null) }})" class="flex flex-col gap-8 animate-fade-up">
        {{-- ── Header ── --}}
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Nouvelle réservation</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Prendre un rendez-vous</h1>
            <p class="max-w-xl text-sm leading-6 text-luxury-secondary">Choisissez un service, sélectionnez une date et choisissez un horaire disponible.</p>
        </header>

        {{-- ── Progress Steps ── --}}
        <div class="flex items-center gap-3">
            <template x-for="(label, i) in ['Service', 'Date', 'Heure']" :key="i">
                <div class="flex items-center gap-3">
                    <button type="button"
                        @click="i < step && goToStep(i)"
                        :class="i <= step
                            ? 'border-luxury-gold bg-luxury-gold/15 text-luxury-gold'
                            : 'border-white/10 bg-white/[0.03] text-zinc-500'"
                        class="flex items-center gap-2 rounded-full border px-4 py-2 font-display text-[10px] font-bold uppercase tracking-widest transition"
                        :disabled="i > step">
                        <span class="grid size-5 place-items-center rounded-full text-[9px] font-black"
                              :class="i < step ? 'bg-luxury-gold text-black' : (i === step ? 'bg-luxury-gold/30 text-luxury-gold' : 'bg-white/10 text-zinc-500')"
                              x-text="i < step ? '✓' : (i + 1)"></span>
                        <span x-text="label"></span>
                    </button>
                    <span x-show="i < 2" class="h-px w-6 bg-white/10"></span>
                </div>
            </template>
        </div>

        {{-- ══════════════════════════════════════════════
             STEP 0 — Choose Service
             ══════════════════════════════════════════════ --}}
        <section x-show="step === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($services as $service)
                <button type="button"
                    @click="selectService({{ Js::from(['id' => $service->id, 'name' => $service->name, 'description' => $service->description, 'price' => (float) $service->price, 'duration' => $service->duration]) }})"
                    :class="selectedService?.id === '{{ $service->id }}'
                        ? 'border-luxury-gold ring-2 ring-luxury-gold/50 shadow-luxury-gold/10'
                        : 'border-luxury-border/60 hover:border-luxury-gold/50'"
                    class="group bg-luxury-bg border rounded-2xl overflow-hidden transition-all duration-500 flex flex-col h-full shadow-lg text-left cursor-pointer">
                    <!-- Image container -->
                    <div class="relative h-64 overflow-hidden bg-black/40 w-full">
                        @php
                            $imgUrl = ($service->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->image_path))
                                ? asset('storage/' . $service->image_path)
                                : null;
                            if (!$imgUrl) {
                                $nameLower = strtolower($service->name);
                                $imgUrl = 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=600&q=80';
                                if (str_contains($nameLower, 'coupe') || str_contains($nameLower, 'haircut') || str_contains($nameLower, 'cheveux') || str_contains($nameLower, 'brushing')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($nameLower, 'barbe') || str_contains($nameLower, 'beard')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($nameLower, 'shave') || str_contains($nameLower, 'rasage')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1517832606589-7a598bb03b15?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($nameLower, 'color') || str_contains($nameLower, 'teinture')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1605497746444-17dbd873c988?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($nameLower, 'visage') || str_contains($nameLower, 'facial') || str_contains($nameLower, 'soin') || str_contains($nameLower, 'masque') || str_contains($nameLower, 'gommage')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($nameLower, 'manucure') || str_contains($nameLower, 'pédicure') || str_contains($nameLower, 'pedicure')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($nameLower, 'pack') || str_contains($nameLower, 'complet') || str_contains($nameLower, 'combo')) {
                                    $imgUrl = 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80';
                                }
                            }
                        @endphp
                        <img src="{{ $imgUrl }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale brightness-90 group-hover:grayscale-0 group-hover:brightness-100"
                            alt="{{ $service->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-luxury-bg via-transparent to-transparent opacity-60 pointer-events-none"></div>

                        <!-- Category/Badge -->
                        <div class="absolute top-4 right-4 bg-luxury-bg/85 border border-luxury-gold/30 px-3 py-1 rounded-full text-[9px] uppercase tracking-widest text-luxury-gold font-display backdrop-blur-sm pointer-events-none">
                            Service Premium
                        </div>
                    </div>

                    <!-- Card details -->
                    <div class="p-6 md:p-8 flex flex-col flex-grow w-full">
                        <div class="flex items-baseline justify-between mb-4">
                            <h3 class="font-display font-bold text-xl text-white group-hover:text-luxury-gold transition-colors duration-300 uppercase tracking-tight">
                                {{ $service->name }}
                            </h3>
                            <span class="text-xl font-display font-semibold text-luxury-gold">DH {{ number_format($service->price, 0) }}</span>
                        </div>

                        <p class="text-luxury-secondary text-sm font-light leading-relaxed mb-6 flex-grow">
                            {{ $service->description ?? 'Soin haut de gamme personnalisé selon votre style et vos préférences.' }}
                        </p>

                        <div class="pt-6 border-t border-luxury-border/60 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs text-luxury-secondary font-display uppercase tracking-wider">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-luxury-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $service->duration }} min
                            </span>

                            <span class="text-xs uppercase tracking-widest font-display text-white group-hover:text-luxury-gold transition-colors duration-300 font-bold flex items-center gap-1">
                                Choisir <span>→</span>
                            </span>
                        </div>
                    </div>
                </button>
            @endforeach
        </section>

        {{-- ══════════════════════════════════════════════
             STEP 1 — Pick Date
             ══════════════════════════════════════════════ --}}
        <section x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">
            {{-- Mini Calendar --}}
            <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#111113] shadow-2xl shadow-black/30">
                <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 sm:px-7">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Choisissez une date</p>
                        <h2 class="mt-1 font-display text-xl font-bold text-white capitalize" x-text="calendarTitle"></h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="prevMonth()" aria-label="Previous month" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">
                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </button>
                        <button type="button" @click="goToToday()" class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-white transition hover:border-luxury-gold/50 cursor-pointer">Aujourd'hui</button>
                        <button type="button" @click="nextMonth()" aria-label="Next month" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">
                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 border-b border-white/10 bg-white/[0.02]">
                    <template x-for="d in ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim']" :key="d">
                        <div class="px-3 py-3 text-center font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary" x-text="d"></div>
                    </template>
                </div>

                <div class="grid grid-cols-7">
                    <template x-for="day in calendarDays" :key="day.date">
                        <button type="button"
                            @click="day.selectable && selectDate(day.date)"
                            :disabled="!day.selectable"
                            :class="{
                                'bg-luxury-gold/[0.08] ring-1 ring-inset ring-luxury-gold/40': selectedDate === day.date,
                                'bg-luxury-gold text-black': day.isToday && selectedDate !== day.date,
                                'bg-black/20 text-zinc-600': !day.currentMonth,
                                'text-zinc-600 cursor-not-allowed line-through': !day.selectable && day.currentMonth,
                                'text-zinc-300 hover:bg-white/[0.05] cursor-pointer': day.selectable && selectedDate !== day.date && !day.isToday,
                            }"
                            class="flex min-h-14 items-center justify-center border-b border-r border-white/[0.07] p-2 font-display text-sm font-semibold transition">
                            <span x-text="day.day"
                                  :class="selectedDate === day.date ? 'bg-luxury-gold text-black' : ''"
                                  class="grid size-8 place-items-center rounded-full"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Selected service summary --}}
            <div class="flex flex-col gap-5">
                <div class="rounded-3xl border border-white/10 bg-[#111113] p-5">
                    <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">Service sélectionné</p>
                    <h3 class="mt-2 font-display text-lg font-bold text-white" x-text="selectedService?.name"></h3>
                    <p class="mt-1 text-xs text-luxury-secondary" x-text="selectedService?.description"></p>
                    <div class="mt-4 flex items-center justify-between border-t border-white/[0.06] pt-4">
                        <span class="text-xs font-bold text-luxury-secondary"><span x-text="selectedService?.duration"></span> min</span>
                        <span class="font-display text-xl font-black text-luxury-gold"><span x-text="selectedService?.price"></span> DH</span>
                    </div>
                </div>

                <button type="button" @click="step = 0" class="flex items-center gap-2 self-start rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">
                    &larr; Changer de service
                </button>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════
             STEP 2 — Pick Time Slot + Confirm
             ══════════════════════════════════════════════ --}}
        <section x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_22rem]">
            {{-- Slot grid --}}
            <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#111113] shadow-2xl shadow-black/30">
                <div class="border-b border-white/10 px-5 py-4 sm:px-7">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Horaires disponibles</p>
                    <h2 class="mt-1 font-display text-xl font-bold text-white capitalize" x-text="selectedDateLabel"></h2>
                </div>

                <div class="p-5 sm:p-7">
                    {{-- Loading state --}}
                    <div x-show="slotsLoading" class="py-12 text-center text-sm text-luxury-secondary">
                        Chargement des créneaux disponibles...
                    </div>

                    {{-- No slots --}}
                    <div x-show="!slotsLoading && slots.length === 0" class="rounded-2xl border border-dashed border-white/10 px-4 py-12 text-center">
                        <p class="text-sm font-medium text-white">Aucun créneau disponible</p>
                        <p class="mt-1 text-xs text-luxury-secondary">Cette journée ne dispose d'aucun créneau libre. Essayez une autre date.</p>
                    </div>

                    {{-- Slot buttons --}}
                    <div x-show="!slotsLoading && slots.length > 0" class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6">
                        <template x-for="slot in slots" :key="slot.time">
                            <button type="button"
                                @click="slot.available && selectSlot(slot.time)"
                                :disabled="!slot.available"
                                :class="slot.available
                                    ? (selectedSlot === slot.time
                                        ? 'border-luxury-gold bg-luxury-gold/20 text-luxury-gold ring-1 ring-luxury-gold/40'
                                        : 'border-white/10 bg-white/[0.03] text-white hover:border-luxury-gold/40 hover:bg-luxury-gold/[0.06] hover:text-luxury-gold cursor-pointer')
                                    : 'border-white/[0.05] bg-white/[0.01] text-zinc-600 line-through cursor-not-allowed'"
                                class="rounded-xl border px-3 py-3 font-display text-sm font-bold transition">
                                <span x-text="slot.time"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Confirmation panel --}}
            <div class="flex flex-col gap-5">
                <div class="rounded-3xl border border-white/10 bg-[#111113] p-5" :class="selectedSlot ? 'border-luxury-gold/30' : ''">
                    <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">Récapitulatif de la réservation</p>

                    <div class="mt-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Service</span>
                            <span class="text-sm font-semibold text-white" x-text="selectedService?.name"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Date</span>
                            <span class="text-sm font-semibold text-white capitalize" x-text="selectedDateLabel"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Heure</span>
                            <span class="text-sm font-semibold" :class="selectedSlot ? 'text-luxury-gold' : 'text-zinc-500'" x-text="selectedSlot || '—'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Durée</span>
                            <span class="text-sm font-semibold text-white"><span x-text="selectedService?.duration"></span> min</span>
                        </div>
                        <div class="border-t border-white/[0.06] pt-3 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-widest text-luxury-secondary">Total</span>
                            <span class="font-display text-xl font-black text-luxury-gold"><span x-text="selectedService?.price"></span> DH</span>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mt-4">
                        <label for="booking-notes" class="block text-[10px] font-bold uppercase tracking-widest text-luxury-secondary mb-2">Remarques (optionnel)</label>
                        <textarea id="booking-notes" x-model="notes" rows="2" placeholder="Toute demande particulière..." class="w-full rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold"></textarea>
                    </div>

                    {{-- Submit --}}
                    <form method="POST" action="{{ route('appointments.store') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="service_id" :value="selectedService?.id">
                        <input type="hidden" name="appointment_at" :value="appointmentDateTime">
                        <input type="hidden" name="notes" :value="notes">
                        <button type="submit"
                            :disabled="!selectedSlot"
                            :class="selectedSlot
                                ? 'bg-luxury-gold text-luxury-bg hover:bg-white cursor-pointer'
                                : 'bg-zinc-800 text-zinc-500 cursor-not-allowed'"
                            class="w-full rounded-full px-5 py-3.5 font-display text-xs font-bold uppercase tracking-widest transition">
                            Confirmer la réservation
                        </button>
                    </form>
                </div>

                <button type="button" @click="step = 1; selectedSlot = null" class="flex items-center gap-2 self-start rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">
                    &larr; Changer de date
                </button>
            </div>
        </section>
    </div>

    <script>
        function bookingWizard(preselectedService = null) {
            const today = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            const toLocalDateStr = (dt) => `${dt.getFullYear()}-${pad(dt.getMonth() + 1)}-${pad(dt.getDate())}`;
            const todayStr = toLocalDateStr(today);

            return {
                step: preselectedService ? 1 : 0,
                selectedService: preselectedService,
                selectedDate: null,
                selectedSlot: null,
                notes: '',
                slots: [],
                slotsLoading: false,

                // Calendar state
                calendarYear: today.getFullYear(),
                calendarMonth: today.getMonth(), // 0-indexed

                get calendarTitle() {
                    return new Date(this.calendarYear, this.calendarMonth).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
                },

                get calendarDays() {
                    const firstDay = new Date(this.calendarYear, this.calendarMonth, 1);
                    const lastDay = new Date(this.calendarYear, this.calendarMonth + 1, 0);

                    // Monday-based week start
                    let startOffset = firstDay.getDay() - 1;
                    if (startOffset < 0) startOffset = 6;

                    const days = [];

                    // Previous month fill
                    const prevLast = new Date(this.calendarYear, this.calendarMonth, 0);
                    for (let i = startOffset - 1; i >= 0; i--) {
                        const d = prevLast.getDate() - i;
                        const dt = new Date(this.calendarYear, this.calendarMonth - 1, d);
                        days.push(this.makeDayObj(dt, false));
                    }

                    // Current month
                    for (let d = 1; d <= lastDay.getDate(); d++) {
                        const dt = new Date(this.calendarYear, this.calendarMonth, d);
                        days.push(this.makeDayObj(dt, true));
                    }

                    // Next month fill (complete the last week row)
                    const remaining = 7 - (days.length % 7);
                    if (remaining < 7) {
                        for (let d = 1; d <= remaining; d++) {
                            const dt = new Date(this.calendarYear, this.calendarMonth + 1, d);
                            days.push(this.makeDayObj(dt, false));
                        }
                    }

                    return days;
                },

                makeDayObj(dt, currentMonth) {
                    const dateStr = toLocalDateStr(dt);
                    const isPast = dateStr < todayStr;
                    return {
                        date: dateStr,
                        day: dt.getDate(),
                        currentMonth,
                        isToday: dateStr === todayStr,
                        selectable: currentMonth && !isPast,
                    };
                },

                get selectedDateLabel() {
                    if (!this.selectedDate) return '—';
                    const dt = new Date(this.selectedDate + 'T12:00:00');
                    return dt.toLocaleDateString('fr-FR', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
                },

                get appointmentDateTime() {
                    if (!this.selectedDate || !this.selectedSlot) return '';
                    return `${this.selectedDate} ${this.selectedSlot}:00`;
                },

                prevMonth() {
                    if (this.calendarMonth === 0) {
                        this.calendarMonth = 11;
                        this.calendarYear--;
                    } else {
                        this.calendarMonth--;
                    }
                },

                nextMonth() {
                    if (this.calendarMonth === 11) {
                        this.calendarMonth = 0;
                        this.calendarYear++;
                    } else {
                        this.calendarMonth++;
                    }
                },

                goToToday() {
                    this.calendarYear = today.getFullYear();
                    this.calendarMonth = today.getMonth();
                },

                goToStep(i) {
                    this.step = i;
                    if (i < 2) {
                        this.selectedSlot = null;
                        this.slots = [];
                    }
                    if (i < 1) {
                        this.selectedDate = null;
                    }
                },

                selectService(service) {
                    this.selectedService = service;
                    this.selectedDate = null;
                    this.selectedSlot = null;
                    this.slots = [];
                    this.step = 1;
                },

                async selectDate(date) {
                    this.selectedDate = date;
                    this.selectedSlot = null;
                    this.step = 2;
                    await this.fetchSlots();
                },

                selectSlot(time) {
                    this.selectedSlot = time;
                },

                async fetchSlots() {
                    this.slotsLoading = true;
                    this.slots = [];
                    try {
                        const url = new URL('{{ route("appointments.available-slots") }}', window.location.origin);
                        url.searchParams.set('date', this.selectedDate);
                        url.searchParams.set('service_id', this.selectedService.id);

                        const res = await fetch(url, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (!res.ok) throw new Error('Failed to load slots');
                        const data = await res.json();

                        const now = new Date();
                        const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
                        const currentMinutes = now.getHours() * 60 + now.getMinutes();

                        this.slots = data.slots.map(slot => {
                            if (this.selectedDate === todayStr) {
                                const [h, m] = slot.time.split(':').map(Number);
                                if ((h * 60 + m) <= currentMinutes) {
                                    return { ...slot, available: false };
                                }
                            }
                            return slot;
                        });
                    } catch (e) {
                        console.error(e);
                        this.slots = [];
                    } finally {
                        this.slotsLoading = false;
                    }
                },
            };
        }
    </script>
@endsection
