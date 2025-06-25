import { format, parseISO } from 'date-fns';

export const statusColors: { [key: string]: string } = {
    pending: 'border-yellow-500 text-yellow-500',
    under_review: 'border-orange-500 text-orange-500',
    resolved: 'border-green-500 text-green-500',
    verified: 'border-blue-500 text-blue-500',
};

// Severity colors mapping
export const severityColors: { [key: string]: string } = {
    critical: 'bg-red-500',
    high: 'bg-orange-500',
    medium: 'bg-yellow-500',
    low: 'bg-green-500',
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
        return dateString;
    }
};
