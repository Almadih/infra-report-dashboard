<script setup lang="ts">
import DateRangeFilter from '@/components/DateRangeFilter.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, ModelPagination, Report, ReportFilters } from '@/types';
import { flattenFilters, formatDate, severityColors, statusColors } from '@/utils';
import { Head, router } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { ref, watch } from 'vue';

type props = {
    reports: ModelPagination<Report>;
    filters: ReportFilters;
};
const props = defineProps<props>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports',
        href: '/reports',
    },
];

const filters = ref({
    severity: {
        critical: props.filters?.severity?.critical ?? true,
        high: props.filters?.severity?.high ?? true,
        medium: props.filters?.severity?.medium ?? true,
        low: props.filters?.severity?.low ?? true,
    },
    status: {
        pending: props.filters?.status?.pending ?? true,
        under_review: props.filters?.status?.under_review ?? true,
        verified: props.filters?.status?.verified ?? true,
        resolved: props.filters?.status?.resolved ?? true,
    },
    location: props.filters?.location ?? '',
    date: {
        start: props.filters?.date?.start ?? '',
        end: props.filters?.date?.end ?? '',
    },
});

watch(
    filters,
    (newFilters) => {
        const query = flattenFilters(newFilters);
        router.get(route('reports.index'), query, {
            preserveState: true,
            replace: true,
        });
    },
    { deep: true },
);

const handlePageChange = (page: number) => {
    const query = flattenFilters(filters.value);

    router.get(
        route('reports.index'),
        { page, ...query },
        {
            preserveState: true,
        },
    );
};
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4">
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="outline" class="h-8 gap-1">
                            <span>Status</span>
                            <ChevronDown class="h-3.5 w-3.5" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Status</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuCheckboxItem
                            :model-value="filters.status.pending"
                            @update:model-value="() => (filters.status.pending = !filters.status.pending)"
                        >
                            Pending
                        </DropdownMenuCheckboxItem>
                        <DropdownMenuCheckboxItem
                            :model-value="filters.status.under_review"
                            @update:model-value="() => (filters.status.under_review = !filters.status.under_review)"
                        >
                            Under Review
                        </DropdownMenuCheckboxItem>
                        <DropdownMenuCheckboxItem
                            :model-value="filters.status.verified"
                            @update:model-value="() => (filters.status.verified = !filters.status.verified)"
                        >
                            Verified
                        </DropdownMenuCheckboxItem>
                        <DropdownMenuCheckboxItem
                            :model-value="filters.status.resolved"
                            @update:model-value="() => (filters.status.resolved = !filters.status.resolved)"
                        >
                            Resolved
                        </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="outline" class="h-8 gap-1">
                            <span>Severity</span>
                            <ChevronDown class="h-3.5 w-3.5" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Severity</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuCheckboxItem
                            :model-value="filters.severity.critical"
                            @update:model-value="() => (filters.severity.critical = !filters.severity.critical)"
                        >
                            Critical
                        </DropdownMenuCheckboxItem>
                        <DropdownMenuCheckboxItem
                            :model-value="filters.severity.high"
                            @update:model-value="() => (filters.severity.high = !filters.severity.high)"
                        >
                            High
                        </DropdownMenuCheckboxItem>
                        <DropdownMenuCheckboxItem
                            :model-value="filters.severity.medium"
                            @update:model-value="() => (filters.severity.medium = !filters.severity.medium)"
                        >
                            Medium
                        </DropdownMenuCheckboxItem>
                        <DropdownMenuCheckboxItem
                            :model-value="filters.severity.low"
                            @update:model-value="() => (filters.severity.low = !filters.severity.low)"
                        >
                            Low
                        </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                </DropdownMenu>

                <div class="space-y-2">
                    <DateRangeFilter :isFullWidth="true" @update:range="(range) => (filters.date = range)" />
                </div>
            </div>
            <Card>
                <CardHeader>
                    <CardTitle>Latest Reports</CardTitle>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Damage Type</TableHead>
                                <TableHead>Address</TableHead>
                                <TableHead>Severity</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Date</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="report in reports.data" :key="report.id">
                                <TableCell class="font-medium">{{ report.id }}</TableCell>
                                <TableCell>{{ report.damage_type.name }}</TableCell>
                                <TableCell>{{ report.address }}</TableCell>
                                <TableCell>
                                    <Badge class="capitalize" :class="severityColors[report.severity.name]">
                                        {{ report.severity.name.replace('_', ' ') }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <Badge variant="outline" class="capitalize" :class="statusColors[report.status.name]"
                                        >{{ report.status.name.replace('_', ' ') }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    {{ formatDate(report.created_at) }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button
                                        @click="
                                            () => {
                                                router.get(route('reports.show', report.id));
                                            }
                                        "
                                    >
                                        View
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div>
                        <Pagination
                            v-slot="{ page }"
                            :items-per-page="reports.per_page"
                            :total="reports.total"
                            :default-page="1"
                            :siblingCount="1"
                            @update:page="handlePageChange"
                        >
                            <PaginationContent v-slot="{ items }">
                                <PaginationPrevious />

                                <template v-for="(item, index) in items" :key="index">
                                    <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page">
                                        {{ item.value }}
                                    </PaginationItem>
                                </template>

                                <PaginationEllipsis :index="10" />

                                <PaginationNext />
                            </PaginationContent>
                        </Pagination>

                        <div class="mt-4 text-sm text-gray-600">Showing {{ reports.from }} to {{ reports.to }} of {{ reports.total }} results</div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
