import { ChartData, ReportsVolumeBySeverity } from '@/types';
import { format, parseISO } from 'date-fns';
import colors from 'tailwindcss/colors.js';

export const statusColors: { [key: string]: string } = {
    pending: 'bg-yellow-100 text-yellow-800 border-yellow-200',
    under_review: 'bg-orange-100 text-orange-800 border-orange-200',
    resolved: 'bg-green-100 text-green-800 border-green-200',
    verified: 'bg-blue-100 text-blue-800 border-blue-200',
};

// Severity colors mapping
export const severityColors: { [key: string]: string } = {
    critical: 'bg-red-600 text-white',
    high: 'bg-red-100 text-red-800 border-red-200',
    medium: 'bg-yellow-100 text-yellow-800 border-yellow-200',
    low: 'bg-green-100 text-green-800 border-green-200',
};

export const statusTailwindColors: { [key: string]: string } = {
    pending: colors.yellow[500],
    under_review: colors.orange[500],
    resolved: colors.green[500],
    verified: colors.blue[500],
};

export const severityTailwindColors: { [key: string]: string } = {
    critical: colors.purple[500],
    high: colors.red[500],
    medium: colors.yellow[500],
    low: colors.green[500],
};

// Utility: Flatten nested filter structure to URL-safe format
export function flattenFilters(filters: any) {
    return {
        ...filters.severity,
        ...filters.status,
        location: filters.location,
        date_start: filters.date.start,
        date_end: filters.date.end,
    };
}

export const formatDate = (dateString: string) => {
    try {
        const date = parseISO(dateString);
        return format(date, "MMM d, yyyy 'at' h:mm a");
    } catch (error) {
        console.log(error);
        return dateString;
    }
};

export function formatReportsBySeverityForChart(data: ReportsVolumeBySeverity[]): ChartData {
    const labelsSet = new Set<string>();
    const severityMap: Record<string, Record<string, number>> = {};

    // Organize by severity and date
    for (const row of data) {
        const { day, severity_name, report_count } = row;
        labelsSet.add(day);

        if (!severityMap[severity_name]) {
            severityMap[severity_name] = {};
        }

        severityMap[severity_name][day] = report_count;
    }

    // Sort labels (dates)
    const labels = Array.from(labelsSet).sort();

    // Build datasets
    const datasets = Object.entries(severityMap).map(([severity, countsByDate]) => ({
        label: severity,
        borderColor: severityTailwindColors[severity],
        backgroundColor: severityTailwindColors[severity],
        tension: 0.2,
        data: labels.map((date) => countsByDate[date] ?? 0),
    }));

    return { labels, datasets };
}

export function calculatePercentage(value: number, total: number) {
    return ((value / total) * 100).toFixed(2);
}
