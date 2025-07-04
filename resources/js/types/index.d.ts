import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface ReportFlag {
    id: number;
    report_id: string;
    type: 'duplicate' | 'low_quality';
    duplicated_report_id?: string;
    reason?: string;
    created_at: string;
    updated_at: string;
    report: Report;
    auto_flagged: boolean;
    confirmed_by_admin: boolean;
    duplicatedReport?: Report;
}

export interface ReputationHistory {
    id: number;
    report_id: string;
    type: 'submit' | 'verify' | 'resolve' | 'low_quality' | 'duplicate';
    user_id: number;
    amount: number;
    created_at: string;
    updated_at: string;
    user: User;
    report: Report;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Report {
    id: string;
    address: string;
    location: Location;
    severity: Severity;
    status: Status;
    damage_type: DamageType;
    created_at: string;
    updated_at: string;
    user: User;
    user_id: number;
    severity_id: number;
    status_id: number;
    damage_type_id: number;
    description: string;
    images: Image[];
    city: string;
    updates: Update[];
}

type Image = {
    id: number;
    url: string;
    report_id: number;
    created_at: string;
    updated_at: string;
};

export interface Severity {
    id: number;
    name: string;
}

export interface Status {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

export interface DamageType {
    id: number;
    name: string;
}

export interface Location {
    type: string;
    coordinates: number[];
}

export interface ModelPagination<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
    links: Link[];
}

export interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

export type Update = {
    id: number;
    text: string;
    report_id: string;
    created_at: string;
    updated_at: string;
};

export type ReportFilters = {
    severity: {
        critical: boolean;
        high: boolean;
        medium: boolean;
        low: boolean;
    };
    status: {
        pending: boolean;
        under_review: boolean;
        verified: boolean;
        resolved: boolean;
    };
    location: string;
    date: {
        start: string;
        end: string;
    };
};

export type BreadcrumbItemType = BreadcrumbItem;
