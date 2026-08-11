@extends('layouts.test')

@section('title', 'Calendrier des rendez-vous')

@section('content')
    @php
        $calendarDays = \Carbon\CarbonPeriod::create($calendarMonth->startOfWeek(), $calendarMonth->endOfMonth()->endOfWeek());
        $appointmentsByDate = $appointments->groupBy(fn ($appointment) => $appointment->appointment_at->toDateString());
        $dailyAppointmentsByHour = $dailyAppointments->groupBy(fn ($appointment) => $appointment->appointment_at->format('H'));
        $dayHours = range(0, 23);
        $selectedStatus = request('status');
        $statusStyles = [
            'pending' => 'border-amber-400/30 bg-amber-400/10 text-amber-200',
            'confirmed' => 'border-violet-400/30 bg-violet-400/15 text-violet-200',
            'completed' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
            'cancelled' => 'border-rose-400/30 bg-rose-400/10 text-rose-200',
            'no_show' => 'border-zinc-500/30 bg-zinc-500/10 text-zinc-300',
        ];
    @endphp

    <div class="flex flex-col gap-7 animate-fade-up">
        <header class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex flex-col gap-2">
                <span class="font-display text-[10px] font-bold uppercase tracking-[0.28em] text-luxury-gold">Espace d'administration</span>
                <h1 class="font-display text-3xl font-black tracking-tight text-white sm:text-4xl">Calendrier des rendez-vous</h1>
                <p class="text-sm text-luxury-secondary">Gérez l'ensemble du planning du salon depuis un seul endroit.</p>
            </div>

            <form action="{{ route('admin.appointments.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row">
                <input type="hidden" name="month" value="{{ $calendarMonth->format('Y-m') }}">
                <input type="hidden" name="date" value="{{ $selectedDate->toDateString() }}">
                <label class="sr-only" for="status">Filtrer par statut</label>
                <select id="status" name="status" class="rounded-full border border-white/10 bg-[#161618] px-4 py-2.5 text-xs text-white focus:border-luxury-gold focus:ring-luxury-gold">
                    <option value="">Tous les statuts</option>
                    @foreach (\App\Enums\AppointmentStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                <button class="rounded-full bg-luxury-gold px-5 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-black transition hover:bg-white cursor-pointer">Appliquer le filtre</button>
            </form>
        </header>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-[#111113] p-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Ce mois-ci</span>
                <p class="mt-2 font-display text-2xl font-black text-white">{{ $appointments->count() }}</p>
            </div>
            <div class="rounded-2xl border border-violet-400/20 bg-violet-400/[0.06] p-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-violet-300">Confirmés</span>
                <p class="mt-2 font-display text-2xl font-black text-white">{{ $appointments->where('status', \App\Enums\AppointmentStatus::Confirmed)->count() }}</p>
            </div>
            <div class="rounded-2xl border border-amber-400/20 bg-amber-400/[0.06] p-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-300">En attente</span>
                <p class="mt-2 font-display text-2xl font-black text-white">{{ $appointments->where('status', \App\Enums\AppointmentStatus::Pending)->count() }}</p>
            </div>
        </div>

        <section class="hidden overflow-hidden rounded-3xl border border-luxury-gold/20 bg-[#111113] shadow-2xl shadow-black/30">
            <div class="flex flex-col gap-4 border-b border-white/10 bg-linear-to-r from-luxury-gold/10 to-transparent px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <div class="flex items-center gap-4">
                    <div class="flex size-14 flex-col items-center justify-center rounded-2xl bg-luxury-gold text-black">
                        <span class="text-[9px] font-black uppercase tracking-wider">{{ $selectedDate->locale('fr')->isoFormat('MMM') }}</span>
                        <span class="font-display text-xl font-black leading-none">{{ $selectedDate->format('d') }}</span>
                    </div>
                    <div>
                        <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">Planning de la journée</p>
                        <h2 class="mt-1 font-display text-xl font-bold text-white capitalize">{{ $selectedDate->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</h2>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.appointments.index', array_filter(['month' => $selectedDate->subDay()->format('Y-m'), 'date' => $selectedDate->subDay()->toDateString(), 'status' => $selectedStatus])) }}" aria-label="Jour précédent" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-white">{{ $dailyAppointments->count() }} rendez-vous</span>
                    <a href="{{ route('admin.appointments.index', array_filter(['month' => $selectedDate->addDay()->format('Y-m'), 'date' => $selectedDate->addDay()->toDateString(), 'status' => $selectedStatus])) }}" aria-label="Jour suivant" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                        <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="max-h-[720px] overflow-y-auto">
                @foreach ($dayHours as $hour)
                    @php($hourAppointments = $dailyAppointmentsByHour->get(str_pad((string) $hour, 2, '0', STR_PAD_LEFT), collect()))
                    <div class="grid min-h-20 grid-cols-[4.5rem_minmax(0,1fr)] border-b border-white/[0.07] sm:grid-cols-[6rem_minmax(0,1fr)]">
                        <div class="border-r border-white/[0.07] px-3 py-4 text-right font-display text-xs font-bold {{ now()->isSameDay($selectedDate) && now()->hour === $hour ? 'text-luxury-gold' : 'text-luxury-secondary' }}">
                            {{ str_pad((string) $hour, 2, '0', STR_PAD_LEFT) }}:00
                        </div>
                        <div class="flex flex-col gap-2 bg-white/[0.01] p-2.5">
                            @foreach ($hourAppointments as $appointment)
                                <a href="{{ route('admin.appointments.show', $appointment) }}" class="group grid gap-3 rounded-xl border p-3 transition hover:brightness-125 sm:grid-cols-[3.5rem_minmax(0,1fr)_auto] sm:items-center {{ $statusStyles[$appointment->status->value] }}">
                                    <span class="font-display text-sm font-black">{{ $appointment->appointment_at->format('H:i') }}</span>
                                    <span class="min-w-0">
                                        <span class="block truncate text-xs font-bold">{{ $appointment->user->full_name }}</span>
                                        <span class="mt-0.5 block truncate text-[10px] opacity-75">{{ $appointment->service->name }} · {{ $appointment->user->phone }}</span>
                                    </span>
                                    <span class="w-fit rounded-full border border-current/20 px-2 py-1 text-[8px] font-bold uppercase tracking-wider">{{ $appointment->status->label() }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </section>

        <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_19rem]">
            <section class="overflow-hidden rounded-3xl border border-white/10 bg-[#111113] shadow-2xl shadow-black/30">
                <div class="flex flex-col gap-4 border-b border-white/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">Planning de l'équipe</p>
                        <h2 class="mt-1 font-display text-xl font-bold text-white capitalize">{{ $calendarMonth->locale('fr')->isoFormat('MMMM YYYY') }}</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.appointments.index', array_filter(['month' => $calendarMonth->subMonth()->format('Y-m'), 'date' => $calendarMonth->subMonth()->startOfMonth()->toDateString(), 'status' => $selectedStatus])) }}" aria-label="Mois précédent" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <a href="{{ route('admin.appointments.index', array_filter(['status' => $selectedStatus])) }}" class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2.5 font-display text-[10px] font-bold uppercase tracking-widest text-white transition hover:border-luxury-gold/50">Aujourd'hui</a>
                        <a href="{{ route('admin.appointments.index', array_filter(['month' => $calendarMonth->addMonth()->format('Y-m'), 'date' => $calendarMonth->addMonth()->startOfMonth()->toDateString(), 'status' => $selectedStatus])) }}" aria-label="Mois suivant" class="inline-flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold/50 hover:text-luxury-gold">
                            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="min-w-[780px]">
                        <div class="grid grid-cols-7 border-b border-white/10 bg-white/[0.02]">
                            @foreach (['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $dayName)
                                <div class="px-3 py-3 text-center font-display text-[10px] font-bold uppercase tracking-widest text-luxury-secondary">{{ $dayName }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7">
                            @foreach ($calendarDays as $day)
                                @php($dayAppointments = $appointmentsByDate->get($day->toDateString(), collect()))
                                <a href="{{ route('admin.appointments.index', array_filter(['month' => $day->format('Y-m'), 'date' => $day->toDateString(), 'status' => $selectedStatus])) }}" data-day-url="{{ route('admin.appointments.day', array_filter(['date' => $day->toDateString(), 'status' => $selectedStatus])) }}" aria-label="Voir {{ $dayAppointments->count() }} rendez-vous pour {{ $day->locale('fr')->isoFormat('D MMMM YYYY') }}" class="js-admin-day group flex min-h-28 flex-col justify-between border-b border-r border-white/[0.07] p-3 transition hover:bg-white/[0.05] {{ $day->isSameDay($selectedDate) ? 'bg-luxury-gold/[0.08] ring-1 ring-inset ring-luxury-gold/40' : ($day->month !== $calendarMonth->month ? 'bg-black/20' : 'bg-[#111113]') }}">
                                    <span class="grid size-8 place-items-center rounded-full text-xs font-semibold transition group-hover:bg-luxury-gold group-hover:text-black {{ $day->isSameDay($selectedDate) ? 'bg-luxury-gold text-black' : ($day->isToday() ? 'border border-luxury-gold text-luxury-gold' : ($day->month !== $calendarMonth->month ? 'text-zinc-600' : 'text-zinc-300')) }}">{{ $day->day }}</span>
                                    @if ($dayAppointments->isNotEmpty())
                                        <div>
                                            <span class="font-display text-2xl font-black text-white">{{ $dayAppointments->count() }}</span>
                                            <span class="ml-1 text-[9px] font-bold uppercase tracking-wider text-luxury-secondary">rendez-vous</span>
                                        </div>
                                    @else
                                        <span class="text-[9px] uppercase tracking-wider text-zinc-700">Aucune réservation</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <aside class="rounded-3xl border border-white/10 bg-[#111113] p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">À suivre</p>
                        <h2 class="mt-1 font-display text-lg font-bold text-white">Prochains clients</h2>
                    </div>
                    <span class="grid size-9 place-items-center rounded-full bg-luxury-gold/15 text-sm text-luxury-gold">{{ $nextAppointments->count() }}</span>
                </div>

                <div class="mt-5 flex flex-col gap-3">
                    @forelse ($nextAppointments as $appointment)
                        <a href="{{ route('admin.appointments.show', $appointment) }}" class="group rounded-2xl border border-white/[0.08] bg-white/[0.025] p-4 transition hover:border-luxury-gold/40">
                            <div class="flex items-start gap-3">
                                <div class="flex min-w-12 flex-col items-center rounded-xl bg-white/[0.06] px-2 py-2">
                                    <span class="text-[9px] font-bold uppercase text-luxury-gold">{{ $appointment->appointment_at->locale('fr')->isoFormat('MMM') }}</span>
                                    <span class="font-display text-lg font-black text-white">{{ $appointment->appointment_at->format('d') }}</span>
                                </div>
                                <div class="min-w-0 grow">
                                    <h3 class="truncate text-sm font-semibold text-white group-hover:text-luxury-gold">{{ $appointment->user->full_name }}</h3>
                                    <p class="mt-1 truncate text-xs text-luxury-secondary">{{ $appointment->service->name }}</p>
                                    <div class="mt-2 flex items-center justify-between gap-2">
                                        <span class="text-[10px] font-bold text-white capitalize">{{ $appointment->appointment_at->locale('fr')->isoFormat('dd, HH:mm') }}</span>
                                        <span class="size-2 rounded-full {{ $appointment->status === \App\Enums\AppointmentStatus::Confirmed ? 'bg-violet-400' : 'bg-amber-400' }}"></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/10 px-4 py-8 text-center text-xs text-luxury-secondary">Aucun rendez-vous à venir.</div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>

    <div id="admin-day-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 p-3 backdrop-blur-sm sm:p-6" role="dialog" aria-modal="true" aria-labelledby="admin-day-modal-title">
        <div class="flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-3xl border border-white/10 bg-[#111113] shadow-2xl shadow-black">
            <div class="flex items-center justify-between gap-4 border-b border-white/10 bg-linear-to-r from-luxury-gold/10 to-transparent px-5 py-4 sm:px-7">
                <div>
                    <p class="font-display text-[10px] font-bold uppercase tracking-widest text-luxury-gold">Planning du jour</p>
                    <h2 id="admin-day-modal-title" class="mt-1 font-display text-lg font-bold text-white">Chargement du planning...</h2>
                    <p id="admin-day-modal-count" class="mt-1 text-xs text-luxury-secondary"></p>
                </div>
                <button type="button" data-close-day-modal aria-label="Fermer le calendrier du jour" class="flex size-10 items-center justify-center rounded-full border border-white/10 text-luxury-secondary transition hover:border-luxury-gold hover:text-luxury-gold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="admin-day-modal-content" class="grow overflow-y-auto"></div>
        </div>
    </div>
@endsection
