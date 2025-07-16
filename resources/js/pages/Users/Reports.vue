<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

import { BreadcrumbItem, ModelPagination as ModelPaginationType, Report, ReportFilters, User } from '@/types';
import DateRangeFilter from '@/components/DateRangeFilter.vue';
import ModelPagination from '@/components/ModelPagination.vue';
import ReportsTable from '@/components/ReportsTable.vue';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Head, router } from '@inertiajs/vue3';
import { pickBy } from 'lodash-es';
import { Filter } from 'lucide-vue-next';
import { ref, watch } from 'vue';

type Props = {
    user: User;
    reports: ModelPaginationType<Report>;
    filters: ReportFilters
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
    },
    {
        title: 'User Details',
        href: route('users.show', props.user.id),
    },
    {
        title: 'User Reports',
        href: route('users.reports', props.user.id),
    }
];

const filtersValues = ref({
    severity: props.filters?.severity ? props.filters.severity.split(',') : [],
    status: props.filters?.status ? props.filters.status.split(',') : [],

    date: props.filters?.date ? { start: props.filters.date.split(',')[0], end: props.filters.date.split(',')[1] } : { start: '', end: '' },
});

watch(
    filtersValues,
    (filters) => {
        const queryParams = {
            'filter[status]': filters.status.join(','),
            'filter[severity]': filters.severity.join(','),
            'filter[date]': filters.date.start != '' ? `${filters.date.start},${filters.date.end}` : '',

        };

        const cleanParams = pickBy(queryParams, (value: any) => value !== '');

        router.get(route('users.reports', props.user.id), cleanParams, {
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
        'filter[date]': filtersValues.value.date,
        page
    };

    const cleanParams = pickBy(queryParams, (value: any) => value !== '');

    router.get(
        route('users.reports', props.user.id),
        cleanParams,
        {
            preserveState: true,
        },
    );
};

</script>


<template>

    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="User Reports" />
        <div class="container mx-auto p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">{{ user.name }}'s Reports</h1>
                <p class="text-muted-foreground">Manage and review all user's reports</p>
            </div>


            <Card class="mb-6">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Filter class="w-5 h-5" />
                        Filters
                    </CardTitle>
                    <CardDescription>Filter and search through reports</CardDescription>
                </CardHeader>
                <CardContent>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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


                        <div class="space-y-2">
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


                        <div class="space-y-2">
                            <Label for="status">Date</Label>

                            <DateRangeFilter :isFullWidth="true"
                                @update:range="(range) => (filtersValues.date = range)" />
                        </div>
                    </div>

                </CardContent>
            </Card>
            <Card>
                <CardContent>
                    <ReportsTable :reports="reports.data" />
                    <ModelPagination :model="reports" @update:page="handlePageChange" />
                </CardContent>
            </Card>
        </div>

    </AppLayout>

</template>