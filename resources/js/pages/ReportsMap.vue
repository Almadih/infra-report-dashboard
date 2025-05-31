<script setup lang="ts">
import DateRangeFilter from '@/components/DateRangeFilter.vue';
import Map from '@/components/Map.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Location, ModelPagination, Report, ReportFilters } from '@/types';
import { flattenFilters, formatDate, severityColors, statusColors } from '@/utils';
import { Head, router } from '@inertiajs/vue3';
import { X, XIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';

type props = {
    reports: ModelPagination<Report>;
    center: Location;
    filters: ReportFilters;
};

const props = defineProps<props>();

const selectedReport = ref<Report | null>(null);

const setCenter = () => {
    if (props.center) {
        return { lat: props.center.coordinates[1], lng: props.center.coordinates[0] };
    }
    return { lat: 30.890040983375705, lng: 29.875583732404525 };
};
const center = ref(setCenter());

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

console.log(props.filters);

watch(
    props,
    (newProps) => {
        if (newProps.center) {
            center.value = { lat: newProps.center.coordinates[1], lng: newProps.center.coordinates[0] };
        }
    },
    { deep: true },
);

// Watch and sync URL when filters change
watch(
    filters,
    (newFilters) => {
        const query = flattenFilters(newFilters);
        router.get(route('reports-map'), query, {
            preserveState: true,
            replace: true,
        });
    },
    { deep: true },
);

const handlePageChange = (page: number) => {
    const query = flattenFilters(filters.value);

    router.get(
        route('reports-map'),
        { page, ...query },
        {
            preserveState: true,
        },
    );
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports Map',
        href: '/reports-map',
    },
];
</script>

<template>
    <Head title="Reports Map" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col">
            <div class="flex flex-1">
                <div class="bg-background w-85 border-r p-4 md:block">
                    <div class="space-y-4">
                        <div>
                            <h3 class="mb-2 text-lg font-semibold">Filters</h3>
                            <Separator class="mb-4" />
                        </div>
                        <h4 class="mb-2 text-sm font-medium">Date</h4>
                        <div class="space-y-2">
                            <DateRangeFilter :isFullWidth="true" @update:range="(range) => (filters.date = range)" />
                        </div>

                        <div>
                            <h4 class="mb-2 text-sm font-medium">Severity</h4>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="severity-critical"
                                        :model-value="filters.severity.critical"
                                        @update:model-value="() => (filters.severity.critical = !filters.severity.critical)"
                                    />
                                    <label htmlFor="severity-critical" class="flex items-center text-sm font-medium">
                                        <Badge :class="severityColors['critical']">Critical</Badge>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="severity-high"
                                        :model-value="filters.severity.high"
                                        @update:model-value="() => (filters.severity.high = !filters.severity.high)"
                                    />
                                    <label htmlFor="severity-high" class="flex items-center text-sm font-medium">
                                        <Badge :class="severityColors['high']">High</Badge>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="severity-medium"
                                        :model-value="filters.severity.medium"
                                        @update:model-value="() => (filters.severity.medium = !filters.severity.medium)"
                                    />
                                    <label htmlFor="severity-medium" class="flex items-center text-sm font-medium">
                                        <Badge :class="severityColors['medium']">Medium</Badge>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="severity-low"
                                        :model-value="filters.severity.low"
                                        @update:model-value="() => (filters.severity.low = !filters.severity.low)"
                                    />
                                    <label htmlFor="severity-low" class="flex items-center text-sm font-medium">
                                        <Badge :class="severityColors['low']">Low</Badge>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-2 text-sm font-medium">Status</h4>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="status-open"
                                        :model-value="filters.status.pending"
                                        @update:model-value="() => (filters.status.pending = !filters.status.pending)"
                                    />
                                    <label htmlFor="status-open" class="flex items-center text-sm font-medium">
                                        <Badge variant="outline" :class="statusColors['pending']"> Pending </Badge>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="status-in-progress"
                                        :model-value="filters.status.under_review"
                                        @update:model-value="() => (filters.status.under_review = !filters.status.under_review)"
                                    />
                                    <label htmlFor="status-in-progress" class="flex items-center text-sm font-medium">
                                        <Badge variant="outline" :class="statusColors['under_review']"> Under Review </Badge>
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="status-in-progress"
                                        :model-value="filters.status.verified"
                                        @update:model-value="() => (filters.status.verified = !filters.status.verified)"
                                    />
                                    <label htmlFor="status-in-progress" class="flex items-center text-sm font-medium">
                                        <Badge variant="outline" :class="statusColors['verified']"> Verified </Badge>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox
                                        id="status-resolved"
                                        :model-value="filters.status.resolved"
                                        @update:model-value="() => (filters.status.resolved = !filters.status.resolved)"
                                    />
                                    <label htmlFor="status-resolved" class="flex items-center text-sm font-medium">
                                        <Badge variant="outline" :class="statusColors['resolved']"> Resolved </Badge>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-2 text-sm font-medium">Location</h4>
                            <div class="relative">
                                <Input placeholder="Filter by location" :value="filters.location" class="pr-8" />

                                <Button
                                    v-if="filters.location"
                                    variant="ghost"
                                    size="icon"
                                    class="absolute top-0 right-0 h-full"
                                    onClick="{clearLocationFilter}"
                                >
                                    <X class="h-4 w-4" />
                                    <span class="sr-only">Clear location filter</span>
                                </Button>
                            </div>
                        </div>

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

                            <div class="mt-4 text-sm text-gray-600">
                                Showing {{ reports.from }} to {{ reports.to }} of {{ reports.total }} results
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative flex-1">
                    <Map :center="center" :zoom="9" class="h-[calc(100vh-4rem)] w-full">
                        <GMapMarker :position="center">
                            {{ center }}
                        </GMapMarker>

                        <GMapMarker
                            @click="selectedReport = report"
                            v-for="report in reports.data"
                            :key="report.id"
                            :position="{
                                lat: report.location.coordinates[1],
                                lng: report.location.coordinates[0],
                            }"
                        />
                    </Map>
                    <Card v-if="selectedReport" class="absolute right-4 bottom-4 left-4 z-10">
                        <CardHeader class="p-3">
                            <CardTitle class="text-sm">
                                <div class="flex items-center">
                                    <span> #{{ selectedReport.id }} </span>

                                    <div class="ml-auto">
                                        <Button variant="ghost" size="icon" @click="selectedReport = null">
                                            <XIcon class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 gap-2 p-3 pt-0 text-xs">
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-muted-foreground text-sm font-medium">Status</dt>
                                    <dd class="text-sm">
                                        <Badge variant="outline" :class="statusColors[selectedReport.status.name as keyof typeof statusColors]">
                                            <span class="flex items-center gap-1 capitalize">
                                                {{ selectedReport.status.name.replace('_', ' ') }}
                                            </span>
                                        </Badge>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground text-sm font-medium">Severity</dt>
                                    <dd class="text-sm">
                                        <Badge
                                            class="capitalize"
                                            :class="severityColors[selectedReport.severity.name as keyof typeof severityColors]"
                                        >
                                            {{ selectedReport.severity.name }}
                                        </Badge>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground text-sm font-medium">Reported On</dt>
                                    <dd class="text-sm">{{ formatDate(selectedReport.created_at) }}</dd>
                                </div>

                                <div>
                                    <dt class="text-muted-foreground text-sm font-medium">Damage Type</dt>
                                    <dd class="text-sm">{{ selectedReport.damage_type.name }}</dd>
                                </div>
                            </dl>
                            <div class="col-span-2 mt-2">
                                <Button size="sm" variant="outline" class="w-full" @click="router.get(route('reports.show', selectedReport.id))">
                                    View Details
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
