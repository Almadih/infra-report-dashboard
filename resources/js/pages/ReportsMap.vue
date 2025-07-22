<script setup lang="ts">
import DateRangeFilter from '@/components/DateRangeFilter.vue';
import Map from '@/components/Map.vue';
import ModelPagination from '@/components/ModelPagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Location, ModelPagination as ModelPaginationType, Report, ReportFilters } from '@/types';
import { formatDate, severityColors, statusColors } from '@/utils';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

import { Head, router } from '@inertiajs/vue3';
import { pickBy } from 'lodash-es';
import { XIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Label from '@/components/ui/label/Label.vue';

type props = {
    reports: ModelPaginationType<Report>;
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

const filtersValues = ref({
    severity: props.filters?.severity ? props.filters.severity.split(',') : [],
    status: props.filters?.status ? props.filters.status.split(',') : [],

    date: props.filters?.date ? { start: props.filters.date.split(',')[0], end: props.filters.date.split(',')[1] } : { start: '', end: '' },
});


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
    filtersValues,
    (filters) => {
        const queryParams = {
            'filter[status]': filters.status.join(','),
            'filter[severity]': filters.severity.join(','),
            'filter[date]': filters.date.start != '' ? `${filters.date.start},${filters.date.end}` : '',

        };

        const cleanParams = pickBy(queryParams, (value: any) => value !== '');

        router.get(route('reports-map'), cleanParams, {
            preserveState: true,
            replace: true,
        });
    },
    { deep: true },
);
const handlePageChange = (page: number) => {
    const queryParams = {
        'filter[status]': filtersValues.value.status.join(','),
        'filter[severity]': filtersValues.value.severity.join(','),
        'filter[date]': filtersValues.value.date.start != '' ? `${filtersValues.value.date.start},${filtersValues.value.date.end}` : '',

        page
    };

    const cleanParams = pickBy(queryParams, (value: any) => value !== '');
    router.get(
        route('reports-map'),
        cleanParams,
        {
            preserveState: true,
        },
    );
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports Map',
        href: route('reports-map'),
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
                            <DateRangeFilter :isFullWidth="true"
                                @update:range="(range) => (filtersValues.date = range)" />
                        </div>

                        <div>
                            <div class="space-y-2">
                                <Label for="status">Status</Label>
                                <Select v-model="filtersValues.status" id="status" multiple>
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="pending">Pending</SelectItem>
                                        <SelectItem value="under_review">Under Review</SelectItem>
                                        <SelectItem value="verified">Verified</SelectItem>
                                        <SelectItem value="resolved">Resolved</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div>
                            <Label for="severity">Severity</Label>
                            <Select v-model="filtersValues.severity" id="severity" multiple>
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select severity" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="critical">Critical</SelectItem>
                                    <SelectItem value="high">High</SelectItem>
                                    <SelectItem value="medium">Medium</SelectItem>
                                    <SelectItem value="low">Low</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <ModelPagination :model="reports" @update:page="handlePageChange" />
                    </div>
                </div>

                <div class="relative flex-1">
                    <Map :center="center" :zoom="9" class="h-[calc(100vh-4rem)] w-full">

                        <GMapMarker @click="selectedReport = report" v-for="report in reports.data" :key="report.id"
                            :position="{
                                lat: report.location.coordinates[1],
                                lng: report.location.coordinates[0],
                            }" />
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
                                        <Badge variant="outline"
                                            :class="statusColors[selectedReport.status.name as keyof typeof statusColors]">
                                            <span class="flex items-center gap-1 capitalize">
                                                {{ selectedReport.status.name.replace('_', ' ') }}
                                            </span>
                                        </Badge>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground text-sm font-medium">Severity</dt>
                                    <dd class="text-sm">
                                        <Badge class="capitalize"
                                            :class="severityColors[selectedReport.severity.name as keyof typeof severityColors]">
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
                                <Button size="sm" variant="outline" class="w-full"
                                    @click="router.get(route('reports.show', selectedReport.id))">
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
