import { format, parseISO } from 'date-fns';

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
