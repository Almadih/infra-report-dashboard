<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, ModelPagination as ModelPaginationType, ReportFlag } from '@/types';
import { formatDate } from '@/utils';
import { Head, Link, router } from '@inertiajs/vue3';
import { Bot, CheckCircle, Clock, ExternalLink, Eye, Filter, User } from 'lucide-vue-next';
import { reactive, watch } from 'vue';
import { pickBy, debounce } from 'lodash-es';
import ModelPagination from '@/components/ModelPagination.vue';


type Props = {
    flags: ModelPaginationType<ReportFlag>;
    // Add filters prop to receive initial state from Laravel
    filters: {
        status: string | null;
        type: string | null;
        flagged_by: string | null;
    };
};
const props = defineProps<Props>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports Flags',
        href: route('report-flags.index'),
    },
];

const filters = reactive({
    status: props.filters.status || "all",
    type: props.filters.type || "all",
    flagged_by: props.filters.flagged_by || "all",
});



watch(filters, debounce(() => {
    // Map frontend values to backend query params
    const queryParams = {
        'filter[confirmed_by_admin]': filters.status === 'confirmed' ? true : (filters.status === 'pending' ? false : undefined),
        'filter[type]': filters.type !== 'all' ? filters.type : undefined,
        'filter[auto_flagged]': filters.flagged_by === 'auto' ? true : (filters.flagged_by === 'manual' ? false : undefined),
    };

    // Use pickBy from lodash to remove any undefined filters
    const cleanParams = pickBy(queryParams, (value: any) => value !== undefined);

    router.get(
        route('report-flags.index'),
        cleanParams,
        {
            preserveState: true,
            replace: true, // Avoids polluting browser history with filter changes
        },
    );
}, 100));


/**
 * Handle pagination changes, preserving the current filters.
 */
const handlePageChange = (page: number) => {
    const queryParams = {
        'filter[confirmed_by_admin]': filters.status === 'confirmed' ? true : (filters.status === 'pending' ? false : undefined),
        'filter[type]': filters.type !== 'all' ? filters.type : undefined,
        'filter[auto_flagged]': filters.flagged_by === 'auto' ? true : (filters.flagged_by === 'manual' ? false : undefined),
        page,
    };

    const cleanParams = pickBy(queryParams, (value: any) => value !== undefined);

    router.get(
        route('report-flags.index'),
        cleanParams,
        {
            preserveState: true,
        },
    );
};


</script>



<template>

    <Head title="Reports Flags" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">Report Flags</h1>
                <p class="text-muted-foreground">Manage and review all flagged reports</p>
            </div>


            <Card class="mb-6">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Filter class="w-5 h-5" />
                        Filters
                    </CardTitle>
                    <CardDescription>Filter and search through flagged reports</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <Label for="status">Status</Label>
                            <!-- Use v-model for two-way data binding -->
                            <Select v-model="filters.status" id="status">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Statuses</SelectItem>
                                    <SelectItem value="confirmed">Confirmed</SelectItem>
                                    <SelectItem value="pending">Pending Review</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="type">Type</Label>
                            <Select v-model="filters.type" id="type">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Types</SelectItem>
                                    <SelectItem value="low_quality">Low Quality</SelectItem>
                                    <SelectItem value="duplicate">Duplicate</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="flagged_by">Flagged By</Label>
                            <!-- Corrected SelectItem values to be more logical -->
                            <Select v-model="filters.flagged_by" id="flagged_by">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select who flagged" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All</SelectItem>
                                    <SelectItem value="auto">Auto</SelectItem>
                                    <SelectItem value="manual">Manual</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="p-0">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Report ID</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Flagged By</TableHead>
                                <TableHead>Created</TableHead>
                                <TableHead>Duplicate</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="flag in flags.data" :key="flag.id">
                                <TableCell class="font-medium">#{{ flag.id }}</TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <code
                                            class="text-xs bg-muted px-2 py-1 rounded">{{ flag.report_id.slice(0, 8) }}...</code>
                                        <Link :href="route('reports.show', flag.report_id)"
                                            class="text-blue-600 hover:text-blue-800">
                                        <ExternalLink class="w-3 h-3" />
                                        </Link>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge variant="secondary" class="capitalize">
                                        {{ flag.type.replace('_', ' ') }}
                                    </Badge>
                                </TableCell>
                                <TableCell>


                                    <Badge v-if="flag.confirmed_by_admin" variant="default"
                                        class="bg-green-100 text-green-800 border-green-200">
                                        <CheckCircle class="w-3 h-3 mr-1" />
                                        Confirmed
                                    </Badge>

                                    <Badge v-else variant="secondary"
                                        class="bg-yellow-100 text-yellow-800 border-yellow-200">
                                        <Clock class="w-3 h-3 mr-1" />
                                        Pending
                                    </Badge>
                                </TableCell>
                                <TableCell>

                                    <div v-if="flag.auto_flagged"
                                        class="flex items-center text-sm text-muted-foreground">
                                        <Bot class="w-4 h-4 mr-1" />
                                        Auto
                                    </div>

                                    <div v-else class="flex items-center text-sm text-muted-foreground">
                                        <User class="w-4 h-4 mr-1" />
                                        Manually
                                    </div>
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ formatDate(flag.created_at) }}
                                </TableCell>
                                <TableCell>

                                    <div v-if="flag.duplicated_report_id" class="flex items-center gap-1">
                                        <code class="text-xs bg-muted px-1 py-0.5 rounded">
                          {{ flag.duplicated_report_id.slice(0, 8) }}...
                        </code>
                                        <Link :href="route('reports.show', flag.duplicated_report_id)"
                                            class="text-blue-600 hover:text-blue-800">
                                        <ExternalLink class="w-3 h-3" />
                                        </Link>
                                    </div>

                                    <span v-else class="text-muted-foreground text-sm">-</span>

                                </TableCell>
                                <TableCell class="text-right">
                                    <Button asChild variant="outline">

                                        <Link :href="route('report-flags.show', flag.id)"
                                            class="flex items-center justify-end">
                                        View Details
                                        <Eye class=" h-4 w-4" />
                                        </Link>

                                    </Button>
                                </TableCell>
                            </TableRow>

                        </TableBody>
                    </Table>
                    <ModelPagination :model="flags" @update:page="handlePageChange" />
                </CardContent>
            </Card>


        </div>
    </AppLayout>
</template>