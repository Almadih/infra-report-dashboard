<script setup lang="ts">
import ReportsHeatMap from '@/components/ReportsHeatMap.vue';
import ReportsTable from '@/components/ReportsTable.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Report, type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Activity, AlertTriangle, Clock, ExternalLink, FileText } from 'lucide-vue-next';
interface props {
    reports: Report[];
    stats: {
        totalReports: {
            value: number;
            details: string;
        };
        todaysReports: {
            value: number;
            details: string;
        };
        pendingReports: {
            value: number;
            details: string;
        };
        criticalReports: {
            value: number;
            details: string;
        };
    };
}

defineProps<props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
];

const formatNumber = (num: number) => {
    return num.toLocaleString();
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex min-h-screen flex-col">
                <main class="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Total Reports</CardTitle>
                                <FileText class="text-muted-foreground h-4 w-4" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ formatNumber(stats.totalReports.value) }}</div>
                                <p class="text-muted-foreground text-xs">{{ stats.totalReports.details }}</p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Today's Reports</CardTitle>
                                <Clock class="text-muted-foreground h-4 w-4" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ formatNumber(stats.todaysReports.value) }}</div>
                                <p class="text-muted-foreground text-xs">{{ stats.todaysReports.details }}</p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Open Reports</CardTitle>
                                <Activity class="text-muted-foreground h-4 w-4" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ formatNumber(stats.pendingReports.value) }}</div>
                                <p class="text-muted-foreground text-xs">{{ stats.pendingReports.details }}</p>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Critical Reports</CardTitle>
                                <AlertTriangle class="h-4 w-4 text-red-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ formatNumber(stats.criticalReports.value) }}</div>
                                <p class="text-muted-foreground text-xs">{{ stats.criticalReports.details }}</p>
                            </CardContent>
                        </Card>
                    </div>
                    <div class="grid gap-4 md:grid-cols-1">
                        <Card class="col-span-4">
                            <CardHeader>
                                <CardTitle>Last 7 Days Reports Heatmap</CardTitle>
                                <CardDescription> Heatmap of last 7 days reports showing the distribution and volume of
                                    reports. </CardDescription>
                            </CardHeader>
                            <CardContent class="pl-2">
                                <ReportsHeatMap />
                            </CardContent>
                        </Card>
                    </div>
                    <div>
                        <Card>
                            <CardHeader>
                                <div class="flex items-center justify-between">

                                    <CardTitle>Latest Reports</CardTitle>
                                    <Button>

                                        <Link :href="route('reports.index')">View All</Link>
                                        <ExternalLink class="w-3 h-3" />

                                    </Button>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <ReportsTable :reports="reports" />
                            </CardContent>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
