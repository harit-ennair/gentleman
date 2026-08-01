@extends('layouts.test')

@section('title', 'Book Appointment')

@section('content')
    <div x-data="bookingWizard({{ Js::from($preselectedService ? ['id' => $preselectedService->id, 'name' => $preselectedService->name, 'description' => $preselectedService->description, 'price' => (float) $preselectedService->price, 'duration' => $preselectedService->duration] : null) }})" class="flex flex-col gap-8 animate-fade-up">
        {{-- ── Header ── --}}
        <header class="flex flex-col gap-2">
            <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">New booking</span>
            <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Book an appointment</h1>
            <p class="max-w-xl text-sm leading-6 text-luxury-secondary">Choose a service, pick a date, and select an available time.</p>
        </header>

        {{-- ── Progress Steps ── --}}
        <div class="flex items-center gap-3">
            <template x-for="(label, i) in ['Service', 'Date', 'Time']" :key="i">
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
        <section x-show="step === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <button type="button"
                    @click="selectService({{ Js::from(['id' => $service->id, 'name' => $service->name, 'description' => $service->description, 'price' => (float) $service->price, 'duration' => $service->duration]) }})"
                    :class="selectedService?.id === '{{ $service->id }}'
                        ? 'border-luxury-gold ring-1 ring-luxury-gold/40 bg-luxury-gold/[0.08]'
                        : 'border-white/10 bg-[#111113] hover:border-white/20 hover:bg-white/[0.04]'"
                    class="group flex flex-col gap-3 rounded-2xl border p-5 text-left transition cursor-pointer">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="font-display text-base font-bold text-white group-hover:text-luxury-gold transition">{{ $service->name }}</h3>
                        <span class="shrink-0 font-display text-lg font-black text-luxury-gold">{{ number_format($service->price, 0) }} <span class="text-xs font-bold">DH</span></span>
                    </div>
                    <p class="text-xs leading-5 text-luxury-secondary line-clamp-2">{{ $service->description }}</p>
                    <div class="flex items-center gap-3 border-t border-white/[0.06] pt-3 text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">
                        <span class="flex items-center gap-1">
                            <svg class="size-3.5 text-luxury-gold/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            {{ $service->duration }} min
                        </span>
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
                        <p class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Pick a date</p>
                        <h2 class="mt-1 font-display text-xl font-bold text-white" x-text="calendarTitle"></h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="prevMonth()" class="grid size-10 place-items-center rounded-full border border-white/10 text-lg text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">&larr;</button>
                        <button type="button" @click="goToToday()" class="rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-white transition hover:border-luxury-gold/50 cursor-pointer">Today</button>
                        <button type="button" @click="nextMonth()" class="grid size-10 place-items-center rounded-full border border-white/10 text-lg text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">&rarr;</button>
                    </div>
                </div>

                <div class="grid grid-cols-7 border-b border-white/10 bg-white/[0.02]">
                    <template x-for="d in ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']" :key="d">
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
                    <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">Selected service</p>
                    <h3 class="mt-2 font-display text-lg font-bold text-white" x-text="selectedService?.name"></h3>
                    <p class="mt-1 text-xs text-luxury-secondary" x-text="selectedService?.description"></p>
                    <div class="mt-4 flex items-center justify-between border-t border-white/[0.06] pt-4">
                        <span class="text-xs font-bold text-luxury-secondary"><span x-text="selectedService?.duration"></span> min</span>
                        <span class="font-display text-xl font-black text-luxury-gold"><span x-text="selectedService?.price"></span> DH</span>
                    </div>
                </div>

                <button type="button" @click="step = 0" class="flex items-center gap-2 self-start rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">
                    &larr; Change service
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
                    <p class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Available times</p>
                    <h2 class="mt-1 font-display text-xl font-bold text-white" x-text="selectedDateLabel"></h2>
                </div>

                <div class="p-5 sm:p-7">
                    {{-- Loading state --}}
                    <div x-show="slotsLoading" class="py-12 text-center text-sm text-luxury-secondary">
                        Loading available times...
                    </div>

                    {{-- No slots --}}
                    <div x-show="!slotsLoading && slots.length === 0" class="rounded-2xl border border-dashed border-white/10 px-4 py-12 text-center">
                        <p class="text-sm font-medium text-white">No available slots</p>
                        <p class="mt-1 text-xs text-luxury-secondary">This day has no open time slots. Try another date.</p>
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
                    <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">Booking summary</p>

                    <div class="mt-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Service</span>
                            <span class="text-sm font-semibold text-white" x-text="selectedService?.name"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Date</span>
                            <span class="text-sm font-semibold text-white" x-text="selectedDateLabel"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Time</span>
                            <span class="text-sm font-semibold" :class="selectedSlot ? 'text-luxury-gold' : 'text-zinc-500'" x-text="selectedSlot || '—'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-luxury-secondary">Duration</span>
                            <span class="text-sm font-semibold text-white"><span x-text="selectedService?.duration"></span> min</span>
                        </div>
                        <div class="border-t border-white/[0.06] pt-3 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-widest text-luxury-secondary">Total</span>
                            <span class="font-display text-xl font-black text-luxury-gold"><span x-text="selectedService?.price"></span> DH</span>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mt-4">
                        <label for="booking-notes" class="block text-[10px] font-bold uppercase tracking-widest text-luxury-secondary mb-2">Notes (optional)</label>
                        <textarea id="booking-notes" x-model="notes" rows="2" placeholder="Any special requests..." class="w-full rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-luxury-gold focus:outline-none focus:ring-1 focus:ring-luxury-gold"></textarea>
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
                            Confirm booking
                        </button>
                    </form>
                </div>

                <button type="button" @click="step = 1; selectedSlot = null" class="flex items-center gap-2 self-start rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold cursor-pointer">
                    &larr; Change date
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
                    return new Date(this.calendarYear, this.calendarMonth).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
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
                    return dt.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
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
                        this.slots = data.slots;
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
