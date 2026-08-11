@extends('layouts.test')

@section('title', 'Mon Agenda')

@section('content')
    @php
        $calendarStart = $calendarMonth->startOfWeek();
        $calendarEnd = $calendarMonth->endOfMonth()->endOfWeek();
        $calendarDays = \Carbon\CarbonPeriod::create($calendarStart, $calendarEnd);
        $appointmentsByDate = $appointments->groupBy(fn ($appointment) => $appointment->appointment_at->toDateString());
        $statusStyles = [
            'pending' => 'border-amber-400/30 bg-amber-400/10 text-amber-200',
            'confirmed' => 'border-violet-400/30 bg-violet-400/15 text-violet-200',
            'completed' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
            'cancelled' => 'border-rose-400/30 bg-rose-400/10 text-rose-200',
            'no_show' => 'border-zinc-500/30 bg-zinc-500/10 text-zinc-300',
        ];
    @endphp

    <div class="flex flex-col gap-8 animate-fade-up">
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Votre planning</span>
                <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Mes rendez-vous</h1>
                <p class="max-w-xl text-sm leading-6 text-luxury-secondary">Tous vos rendez-vous réservés et organisés au même endroit.</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-luxury-gold px-5 py-3 font-display text-xs font-bold uppercase tracking-widest text-luxury-bg transition hover:bg-white">
                <span class="text-lg leading-none">+</span>
                Prendre rendez-vous
            </a>
        </header>

        <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_18rem]">
            <section class="overflow-hidden rounded-3xl border border-white/10 bg-[#111113] shadow-2xl shadow-black/30">
                <div class="flex flex-col gap-4 border-b border-white/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-widest text-luxury-secondary">Calendrier</p>
                        <h2 class="mt-1 font-display text-xl font-bold text-white capitalize">{{ $calendarMonth->locale('fr')->isoFormat('MMMM YYYY') }}</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('appointments.index', ['month' => $calendarMonth->subMonth()->format('Y-m')]) }}" aria-label="Previous month" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <a href="{{ route('appointments.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-white transition hover:border-luxury-gold/50">Aujourd'hui</a>
                        <a href="{{ route('appointments.index', ['month' => $calendarMonth->addMonth()->format('Y-m')]) }}" aria-label="Next month" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="min-w-[720px]">
                        <div class="grid grid-cols-7 border-b border-white/10 bg-white/[0.02]">
                            @foreach (['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                                <div class="px-3 py-3 text-center font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">{{ $dayName }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7">
                            @foreach ($calendarDays as $day)
                                @php($dayAppointments = $appointmentsByDate->get($day->toDateString(), collect()))
                                <div class="min-h-32 border-b border-r border-white/[0.07] p-2.5 {{ $day->month !== $calendarMonth->month ? 'bg-black/20' : 'bg-[#111113]' }}">
                                    <div class="mb-2 flex items-center justify-between">
                                        <span class="grid size-7 place-items-center rounded-full text-xs font-semibold {{ $day->isToday() ? 'bg-luxury-gold text-black' : ($day->month !== $calendarMonth->month ? 'text-zinc-600' : 'text-zinc-300') }}">{{ $day->day }}</span>
                                        @if ($dayAppointments->isNotEmpty())
                                            <span class="text-[9px] font-bold text-luxury-secondary">{{ $dayAppointments->count() }}</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        @foreach ($dayAppointments->take(3) as $appointment)
                                            <a href="{{ route('appointments.show', $appointment) }}" class="rounded-lg border px-2 py-1.5 text-[10px] leading-tight transition hover:brightness-125 {{ $statusStyles[$appointment->status->value] }}">
                                                <span class="block font-bold">{{ $appointment->appointment_at->format('H:i') }}</span>
                                                <span class="block truncate opacity-80">{{ $appointment->service->name }}</span>
                                            </a>
                                        @endforeach
                                        @if ($dayAppointments->count() > 3)
                                            <span class="px-1 text-[9px] text-luxury-secondary">+{{ $dayAppointments->count() - 3 }} de plus</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <aside class="flex flex-col gap-5">
                <div class="rounded-3xl border border-white/10 bg-[#111113] p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">À venir</p>
                            <h2 class="mt-1 font-display text-lg font-bold text-white">Prochains rendez-vous</h2>
                        </div>
                        <span class="grid size-9 place-items-center rounded-full bg-violet-500/15 text-sm text-violet-300">{{ $upcomingAppointments->count() }}</span>
                    </div>

                    <div class="mt-5 flex flex-col gap-3">
                        @forelse ($upcomingAppointments as $appointment)
                            <a href="{{ route('appointments.show', $appointment) }}" class="group rounded-2xl border border-white/[0.08] bg-white/[0.025] p-4 transition hover:border-luxury-gold/40 hover:bg-white/[0.05]">
                                <div class="flex items-start gap-3">
                                    <div class="flex min-w-11 flex-col items-center rounded-xl bg-white/[0.06] px-2 py-2">
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-luxury-gold">{{ $appointment->appointment_at->locale('fr')->isoFormat('MMM') }}</span>
                                        <span class="font-display text-lg font-black text-white">{{ $appointment->appointment_at->format('d') }}</span>
                                    </div>
                                    <div class="min-w-0 grow">
                                        <h3 class="truncate text-sm font-semibold text-white group-hover:text-luxury-gold">{{ $appointment->service->name }}</h3>
                                        <p class="mt-1 text-xs text-luxury-secondary">{{ $appointment->appointment_at->locale('fr')->isoFormat('ddd, HH:mm') }}</p>
                                        <span class="mt-2 inline-flex rounded-full border px-2 py-1 text-[9px] font-bold uppercase tracking-wider {{ $statusStyles[$appointment->status->value] }}">{{ $appointment->status->label() }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-white/10 px-4 py-8 text-center">
                                <p class="text-sm font-medium text-white">Aucun rendez-vous prévu</p>
                                <p class="mt-1 text-xs leading-5 text-luxury-secondary">Réservez un service quand vous le souhaitez.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-luxury-gold/20 bg-linear-to-br from-luxury-gold/15 to-violet-500/10 p-5">
                    <p class="font-serif text-xl italic leading-7 text-white">« Prenez soin de votre style, le reste suivra. »</p>
                    <p class="mt-3 text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Soin Gentleman</p>
                </div>
            </aside>
        </div>
    </div>
@endsection
