import Alpine from 'alpinejs';
import {
    DEFAULT_PIXELS_PER_MINUTE,
    calculateTop,
    calculateHeight,
    detectOverlappingAppointments,
    calculateAppointmentWidth,
    calculateAppointmentLeft,
} from './calendar';

window.Alpine = Alpine;
window.CalendarLayout = {
    DEFAULT_PIXELS_PER_MINUTE,
    calculateTop,
    calculateHeight,
    detectOverlappingAppointments,
    calculateAppointmentWidth,
    calculateAppointmentLeft,
};

Alpine.start();

const dayModal = document.querySelector('#admin-day-modal');

if (dayModal) {
    const modalTitle = dayModal.querySelector('#admin-day-modal-title');
    const modalCount = dayModal.querySelector('#admin-day-modal-count');
    const modalContent = dayModal.querySelector('#admin-day-modal-content');
    const statusClasses = {
        pending: 'border-amber-400/30 bg-amber-400/10 text-amber-200',
        confirmed: 'border-violet-400/30 bg-violet-400/15 text-violet-200',
        completed: 'border-emerald-400/30 bg-emerald-400/10 text-emerald-200',
        cancelled: 'border-rose-400/30 bg-rose-400/10 text-rose-200',
        no_show: 'border-zinc-500/30 bg-zinc-500/10 text-zinc-300',
    };

    // Configurable scale factor: 2 pixels per minute (120px per hour)
    const PIXELS_PER_MINUTE = DEFAULT_PIXELS_PER_MINUTE;
    const LABEL_WIDTH_CLASS = 'w-[4.5rem] sm:w-24';

    const closeModal = () => {
        dayModal.classList.add('hidden');
        dayModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    const statusLabels = {
        pending: 'En attente',
        confirmed: 'Confirmé',
        completed: 'Terminé',
        cancelled: 'Annulé',
        no_show: 'Non présenté',
    };

    const createAppointmentCard = (item, pixelsPerMinute = PIXELS_PER_MINUTE) => {
        const durationMin = item.duration || 30;
        const height = calculateHeight(durationMin, pixelsPerMinute);
        const top = calculateTop(item.startMin, pixelsPerMinute);
        const isCompact = durationMin <= 30;

        const widthPercent = calculateAppointmentWidth(item.totalColumns);
        const leftPercent = calculateAppointmentLeft(item.column, widthPercent);

        const link = document.createElement('a');
        link.href = item.details_url;
        link.style.position = 'absolute';
        link.style.top = `${top}px`;
        link.style.height = `${height}px`;
        link.style.left = `calc(${leftPercent}% + 3px)`;
        link.style.width = `calc(${widthPercent}% - 6px)`;
        link.style.zIndex = '10';

        if (isCompact) {
            // ── Compact single-line card (≤ 30 min) ──
            link.className = `flex items-center gap-2.5 overflow-hidden rounded-lg border px-3 transition hover:brightness-125 ${statusClasses[item.status] ?? statusClasses.no_show}`;

            const time = document.createElement('span');
            time.className = 'font-display text-[11px] font-black shrink-0';
            time.textContent = item.time;

            const sep = document.createElement('span');
            sep.className = 'shrink-0 h-3 w-px bg-current opacity-25';

            const name = document.createElement('span');
            name.className = 'truncate text-[10px] font-semibold';
            name.textContent = item.client;

            const svc = document.createElement('span');
            svc.className = 'hidden sm:inline truncate text-[9px] opacity-60';
            svc.textContent = `${item.service} · ${durationMin}min`;

            const badge = document.createElement('span');
            badge.className = 'ml-auto shrink-0 rounded-full border border-current/20 px-1.5 py-0.5 text-[7px] font-bold uppercase tracking-wider';
            badge.textContent = statusLabels[item.status] ?? item.status;

            link.append(time, sep, name, svc, badge);
        } else {
            // ── Full multi-line card (> 30 min) ──
            link.className = `grid overflow-hidden rounded-xl border transition hover:brightness-125 ${statusClasses[item.status] ?? statusClasses.no_show}`;
            link.style.gridTemplateColumns = '3.5rem minmax(0, 1fr)';

            // Left accent strip with time
            const timeStrip = document.createElement('div');
            timeStrip.className = 'flex flex-col items-center justify-center gap-0.5 border-r border-current/10 bg-current/5 px-1';

            const timeLabel = document.createElement('span');
            timeLabel.className = 'font-display text-sm font-black leading-none';
            timeLabel.textContent = item.time;

            const durLabel = document.createElement('span');
            durLabel.className = 'text-[8px] font-bold uppercase opacity-60';
            durLabel.textContent = `${durationMin}m`;

            timeStrip.append(timeLabel, durLabel);

            // Right content area
            const content = document.createElement('div');
            content.className = 'flex flex-col justify-center gap-0.5 px-3 py-2 min-w-0';

            const nameRow = document.createElement('div');
            nameRow.className = 'flex items-center justify-between gap-2 min-w-0';

            const name = document.createElement('span');
            name.className = 'truncate text-xs font-bold';
            name.textContent = item.client;

            const badge = document.createElement('span');
            badge.className = 'shrink-0 rounded-full border border-current/20 px-2 py-0.5 text-[8px] font-bold uppercase tracking-wider';
            badge.textContent = statusLabels[item.status] ?? item.status;

            nameRow.append(name, badge);

            const details = document.createElement('span');
            details.className = 'truncate text-[10px] opacity-70';
            details.textContent = `${item.service} · ${item.phone ?? 'Sans téléphone'}`;

            content.append(nameRow, details);
            link.append(timeStrip, content);
        }

        return link;
    };

    const renderSchedule = (appointments, pixelsPerMinute = PIXELS_PER_MINUTE) => {
        modalContent.replaceChildren();

        const hourHeight = calculateHeight(60, pixelsPerMinute);
        const wrapper = document.createElement('div');
        wrapper.className = 'flex';

        // --- Hour labels column ---
        const labelsCol = document.createElement('div');
        labelsCol.className = `${LABEL_WIDTH_CLASS} shrink-0 border-r border-white/[0.07]`;

        for (let hour = 0; hour < 24; hour++) {
            const label = document.createElement('div');
            label.className = 'text-right pr-3 font-display text-xs font-bold text-luxury-secondary';
            label.style.height = `${hourHeight}px`;
            label.style.lineHeight = `${hourHeight}px`;
            label.textContent = `${String(hour).padStart(2, '0')}:00`;
            labelsCol.append(label);
        }

        // --- Timeline column ---
        const timelineCol = document.createElement('div');
        timelineCol.className = 'relative grow';
        timelineCol.style.height = `${calculateHeight(24 * 60, pixelsPerMinute)}px`;

        // Draw hour and half-hour guide lines
        for (let hour = 0; hour < 24; hour++) {
            const hourLine = document.createElement('div');
            hourLine.className = 'absolute left-0 right-0 border-b border-white/[0.07]';
            hourLine.style.top = `${calculateTop(hour * 60, pixelsPerMinute)}px`;
            timelineCol.append(hourLine);

            const halfLine = document.createElement('div');
            halfLine.className = 'absolute left-0 right-0 border-b border-dashed border-white/[0.03]';
            halfLine.style.top = `${calculateTop(hour * 60 + 30, pixelsPerMinute)}px`;
            timelineCol.append(halfLine);
        }
        // Final bottom line
        const bottomLine = document.createElement('div');
        bottomLine.className = 'absolute left-0 right-0 border-b border-white/[0.07]';
        bottomLine.style.top = `${calculateTop(24 * 60, pixelsPerMinute)}px`;
        timelineCol.append(bottomLine);

        // Detect overlaps and position cards dynamically
        const items = detectOverlappingAppointments(appointments);
        for (const item of items) {
            timelineCol.append(createAppointmentCard(item, pixelsPerMinute));
        }

        wrapper.append(labelsCol, timelineCol);
        modalContent.append(wrapper);

        // Auto-scroll to 1 hour before the first appointment, or 08:00
        let scrollTarget = calculateTop(8 * 60, pixelsPerMinute);
        if (items.length > 0) {
            scrollTarget = Math.max(0, calculateTop(items[0].startMin - 60, pixelsPerMinute));
        }
        modalContent.scrollTop = scrollTarget;
    };

    document.querySelectorAll('.js-admin-day').forEach((dayLink) => {
        dayLink.addEventListener('click', async (event) => {
            event.preventDefault();
            dayModal.classList.remove('hidden');
            dayModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            modalTitle.textContent = 'Chargement du planning...';
            modalCount.textContent = '';
            modalContent.innerHTML = '<div class="p-10 text-center text-sm text-luxury-secondary">Chargement des rendez-vous...</div>';

            try {
                const response = await fetch(dayLink.dataset.dayUrl, {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (!response.ok) {
                    throw new Error('Impossible de charger cette journée.');
                }

                const schedule = await response.json();
                modalTitle.textContent = schedule.date_label;
                modalCount.textContent = `${schedule.appointments_count} rendez-vous`;
                renderSchedule(schedule.appointments);
            } catch (error) {
                modalTitle.textContent = 'Planning indisponible';
                modalContent.textContent = error.message;
                modalContent.className = 'grow overflow-y-auto p-10 text-center text-sm text-rose-300';
            }
        });
    });

    dayModal.querySelector('[data-close-day-modal]').addEventListener('click', closeModal);
    dayModal.addEventListener('click', (event) => {
        if (event.target === dayModal) {
            closeModal();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
}
