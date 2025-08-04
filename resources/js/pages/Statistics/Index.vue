<script setup lang="ts">
import { AlertTriangle, BarChart3, Calendar, Users } from "lucide-vue-next"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import Chart from 'primevue/chart';
import colors from 'tailwindcss/colors.js'

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import AppLayout from "@/layouts/AppLayout.vue"
import { calculatePercentage, formatReportsBySeverityForChart, severityTailwindColors, statusTailwindColors } from "@/utils"
import { BreadcrumbItem, ReportsByRegion, ReportsBySeverity, ReportsByStatus, ReportsVolume, ReportsVolumeBySeverity, Stats, UserWithReportsCount } from "@/types"
import { computed, ref, watch } from "vue";
import Button from "@/components/ui/button/Button.vue";
import { Head, router } from "@inertiajs/vue3";





type Props = {
    reportsByStatus: ReportsByStatus[];
    reportsBySeverity: ReportsBySeverity[];
    topReporters: UserWithReportsCount[];
    reportsVolume: ReportsVolume[];
    reportsVolumeBySeverity: ReportsVolumeBySeverity[];
    reportsByRegion: ReportsByRegion[];
    timeRange: string;
    stats: Stats;
};

const props = defineProps<Props>();

const selectedTimeRange = ref(props.timeRange ?? "30days")
const selectedTab = ref("overview")

const timeRangeLabel = computed(() => {
    switch (selectedTimeRange.value) {
        case "7days": return "Last 7 Days"
        case "30days": return "Last 30 Days"
        case "90days": return "Last 90 Days"
        case "1year": return "Last Year"
        default: return "Last 30 Days"
    }
})

watch(selectedTimeRange, () => {

    router.get(route('statistics.index', {
        timeRange: selectedTimeRange.value
    }))

})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Statistics',
        href: route('statistics.index'),
    },
];

</script>

<template>

    <Head title="Statistics" />
    <AppLayout :breadcrumbs="breadcrumbs">

        <main class="container mx-auto p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Statistics & Reports</h1>
                    <p class="text-muted-foreground">Comprehensive analytics and insights for your reporting system
                    </p>
                </div>

                <div class="ml-auto flex items-center gap-4">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="outline" size="sm">
                                <Calendar class="mr-2 h-4 w-4" />
                                {{ timeRangeLabel }}
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>Time Range</DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuRadioGroup v-model="selectedTimeRange">
                                <DropdownMenuRadioItem value="7days">Last 7 Days</DropdownMenuRadioItem>
                                <DropdownMenuRadioItem value="30days">Last 30 Days</DropdownMenuRadioItem>
                                <DropdownMenuRadioItem value="90days">Last 90 Days</DropdownMenuRadioItem>
                                <DropdownMenuRadioItem value="1year">Last Year</DropdownMenuRadioItem>
                            </DropdownMenuRadioGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>

            <Tabs v-model="selectedTab" class="space-y-4">
                <TabsList>
                    <TabsTrigger value="overview">Overview</TabsTrigger>
                    <TabsTrigger value="trends">Trends</TabsTrigger>
                    <TabsTrigger value="geographic">Geographic</TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="space-y-4">
                    <!-- Key Metrics -->
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Total Reports</CardTitle>
                                <BarChart3 class="h-4 w-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ stats.total_reports }}</div>

                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Open Reports</CardTitle>
                                <AlertTriangle class="h-4 w-4 text-orange-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ stats.open_reports }}</div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">Active Reporters</CardTitle>
                                <Users class="h-4 w-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ stats.active_reporters }}</div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid gap-4 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Reports by Severity</CardTitle>
                                <CardDescription>Distribution of reports across severity levels</CardDescription>
                            </CardHeader>
                            <CardContent>

                                <Chart type="doughnut" class="h-[500px]" :data="{
                                    labels: reportsBySeverity.map(item => item.name.replace('_', ' ')),
                                    datasets: [
                                        {
                                            label: 'Reports',
                                            data: reportsBySeverity.map(item => item.value),
                                            backgroundColor: reportsBySeverity.map(item => severityTailwindColors[item.name]),

                                        }
                                    ]
                                }" />
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Reports by Status</CardTitle>
                                <CardDescription>Current status distribution of all reports</CardDescription>
                            </CardHeader>
                            <CardContent class="h-[100%]">
                                <Chart type="bar" class="h-[100%]" :data="{
                                    labels: reportsByStatus.map(item => item.name.replace('_', ' ')),
                                    datasets: [
                                        {
                                            label: 'Reports',
                                            data: reportsByStatus.map(item => item.value),
                                            backgroundColor: reportsByStatus.map(item => statusTailwindColors[item.name]),

                                        }
                                    ]
                                }" />
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Bottom Row -->
                    <div class="grid gap-4 md:grid-cols-1">
                        <Card>
                            <CardHeader>
                                <CardTitle>Top Reporters</CardTitle>
                                <CardDescription>Most active users in the reporting system</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(reporter, index) in topReporters" :key="reporter.id"
                                        class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-muted text-sm font-medium">
                                                {{ index + 1 }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium">{{ reporter.name }}</p>
                                                <p class="text-xs text-muted-foreground">{{ reporter.reports_count
                                                }}
                                                    reports
                                                </p>
                                            </div>
                                        </div>
                                        <template>
                                            <div class="h-4 w-4" />
                                        </template>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <TabsContent value="trends" class="space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Report Trends Over Time</CardTitle>
                            <CardDescription>Daily report volume and resolution trends</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Chart type="line" class="w-full" :data="{
                                labels: reportsVolume.map(item => item.day),
                                datasets: [
                                    {
                                        label: 'Reports',
                                        data: reportsVolume.map(item => item.report_count),
                                        fill: true,
                                        tension: 0.3,
                                        backgroundColor: colors.slate[500],
                                    }
                                ]
                            }" />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Severity Trends</CardTitle>
                            <CardDescription>How report severity has changed over time</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Chart type="line" :data="formatReportsBySeverityForChart(reportsVolumeBySeverity)"
                                class="w-full" />
                        </CardContent>
                    </Card>
                </TabsContent>



                <TabsContent value="geographic" class="space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Reports by Location</CardTitle>
                            <CardDescription>Geographic distribution of reports</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-for="(location, index) in reportsByRegion" :key="location.name"
                                class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-muted text-sm font-medium">
                                        {{ index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ location.name }}</p>
                                        <p class="text-xs text-muted-foreground">{{
                                            calculatePercentage(location.count, reportsByRegion.reduce((a, b) =>
                                                a + b.count, 0))}}% of total
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium">{{ location.count }}</p>
                                    <p class="text-xs text-muted-foreground">reports</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </main>
    </AppLayout>

</template>