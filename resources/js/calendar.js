/**
 * Calendar Dynamic Layout System (Google Calendar / Outlook style)
 */

/**
 * Default pixels per minute ratio for the timeline grid.
 * 2 pixels/min = 120px per 60-minute hour.
 */
export const DEFAULT_PIXELS_PER_MINUTE = 2;

/**
 * Calculates the dynamic top (vertical position in pixels) for an appointment.
 *
 * @param {number} minutesSinceStartOfDay - Minutes elapsed from 00:00 (or timeline start).
 * @param {number} [pixelsPerMinute=2] - Scale factor (pixels per minute).
 * @returns {number} Top offset in pixels.
 */
export function calculateTop(minutesSinceStartOfDay, pixelsPerMinute = DEFAULT_PIXELS_PER_MINUTE) {
    const mins = Math.max(0, Number(minutesSinceStartOfDay) || 0);
    const ppm = Number(pixelsPerMinute) || DEFAULT_PIXELS_PER_MINUTE;
    return mins * ppm;
}

/**
 * Calculates the dynamic height in pixels for an appointment based on its duration.
 *
 * @param {number} durationInMinutes - Appointment duration in minutes.
 * @param {number} [pixelsPerMinute=2] - Scale factor (pixels per minute).
 * @returns {number} Height in pixels.
 */
export function calculateHeight(durationInMinutes, pixelsPerMinute = DEFAULT_PIXELS_PER_MINUTE) {
    const duration = Math.max(1, Number(durationInMinutes) || 30);
    const ppm = Number(pixelsPerMinute) || DEFAULT_PIXELS_PER_MINUTE;
    return duration * ppm;
}

/**
 * Detects overlapping appointments and calculates column indexes & cluster dimensions
 * so overlapping appointments render side-by-side without visual collision.
 *
 * @param {Array<Object>} appointments - List of appointment objects.
 * @returns {Array<Object>} Enhanced list of appointments with startMin, endMin, column, totalColumns.
 */
export function detectOverlappingAppointments(appointments) {
    if (!Array.isArray(appointments) || appointments.length === 0) {
        return [];
    }

    // Standardize appointments with startMin and endMin
    const items = appointments.map((appt) => {
        let startMin = 0;
        if (typeof appt.startMin === 'number') {
            startMin = appt.startMin;
        } else if (typeof appt.hour !== 'undefined') {
            const h = parseInt(appt.hour, 10) || 0;
            const m = parseInt(appt.minute, 10) || 0;
            startMin = h * 60 + m;
        } else if (appt.appointment_at) {
            const d = new Date(appt.appointment_at);
            startMin = d.getHours() * 60 + d.getMinutes();
        }

        const duration = Math.max(1, parseInt(appt.duration, 10) || 30);
        const endMin = startMin + duration;

        return {
            ...appt,
            startMin,
            endMin,
            duration,
            column: 0,
            totalColumns: 1,
        };
    });

    // Sort by start time ascending, then by duration descending
    items.sort((a, b) => a.startMin - b.startMin || b.duration - a.duration);

    // Group into connected overlapping clusters
    const clusters = [];
    let currentCluster = [];
    let clusterEnd = -1;

    for (const item of items) {
        if (currentCluster.length === 0) {
            currentCluster.push(item);
            clusterEnd = item.endMin;
        } else if (item.startMin < clusterEnd) {
            // Overlaps with current cluster
            currentCluster.push(item);
            clusterEnd = Math.max(clusterEnd, item.endMin);
        } else {
            // No overlap, finalize current cluster and start new cluster
            clusters.push(currentCluster);
            currentCluster = [item];
            clusterEnd = item.endMin;
        }
    }
    if (currentCluster.length > 0) {
        clusters.push(currentCluster);
    }

    // Assign column index and total columns within each cluster
    const processedItems = [];
    for (const cluster of clusters) {
        const columns = []; // Track the latest endMin in each column

        for (const item of cluster) {
            let placed = false;
            for (let colIndex = 0; colIndex < columns.length; colIndex++) {
                if (item.startMin >= columns[colIndex]) {
                    item.column = colIndex;
                    columns[colIndex] = item.endMin;
                    placed = true;
                    break;
                }
            }
            if (!placed) {
                item.column = columns.length;
                columns.push(item.endMin);
            }
        }

        const numCols = columns.length;
        for (const item of cluster) {
            item.totalColumns = numCols;
            processedItems.push(item);
        }
    }

    return processedItems;
}

/**
 * Calculates the width percentage for an appointment in an overlapping group.
 *
 * @param {number} totalColumns - Total overlapping columns in the cluster.
 * @returns {number} Width percentage (0 to 100).
 */
export function calculateAppointmentWidth(totalColumns) {
    const cols = Math.max(1, Number(totalColumns) || 1);
    return 100 / cols;
}

/**
 * Calculates the left offset percentage for an appointment within its grid column.
 *
 * @param {number} columnIndex - 0-indexed column position.
 * @param {number} widthPercent - Width percentage of each column.
 * @returns {number} Left percentage offset (0 to 100).
 */
export function calculateAppointmentLeft(columnIndex, widthPercent) {
    const col = Math.max(0, Number(columnIndex) || 0);
    const width = Number(widthPercent) || 100;
    return col * width;
}
