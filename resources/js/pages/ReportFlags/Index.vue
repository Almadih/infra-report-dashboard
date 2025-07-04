<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, ModelPagination, ReportFlag } from '@/types';
import { formatDate } from '@/utils';
import { Head, Link, router } from '@inertiajs/vue3';
import { Bot, CheckCircle, Clock, ExternalLink, Eye, Filter, User } from 'lucide-vue-next';



type props = {
    flags: ModelPagination<ReportFlag>;
};
const props = defineProps<props>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports Flags',
        href: '/report-flags',
    },
];

const handlePageChange = (page: number) => {

    router.get(
        route('report-flags.index'),
        { page },
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
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <Label htmlFor="status">Status</Label>
                            <Select value={statusFilter} onValueChange={setStatusFilter}>
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
                            <Label htmlFor="type">Type</Label>
                            <Select value={typeFilter} onValueChange={setTypeFilter}>
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Types</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="mb-4">
                <p class="text-sm text-muted-foreground">
                    Showing {filteredFlags.length} of {flagsData.length} flags
                </p>
            </div>

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
                                    <Link :href="route('report-flags.show', flag.id)"
                                        class="flex items-center justify-end">
                                    <Eye class="mr-2 h-4 w-4" />
                                    View Details
                                    </Link>

                                </TableCell>
                            </TableRow>

                        </TableBody>
                    </Table>
                    <div class="mt-4">
                        <Pagination v-slot="{ page }" :items-per-page="flags.per_page" :total="flags.total"
                            :default-page="1" :siblingCount="1" @update:page="handlePageChange">
                            <PaginationContent v-slot="{ items }">
                                <PaginationPrevious />

                                <template v-for="(item, index) in items" :key="index">
                                    <PaginationItem v-if="item.type === 'page'" :value="item.value"
                                        :is-active="item.value === page">
                                        {{ item.value }}
                                    </PaginationItem>
                                </template>

                                <PaginationEllipsis :index="10" />

                                <PaginationNext />
                            </PaginationContent>
                        </Pagination>

                    </div>
                </CardContent>
            </Card>


        </div>
    </AppLayout>
</template>