<script setup lang="ts">
import ModelPagination from '@/components/ModelPagination.vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, ModelPagination as ModelPaginationType, User } from '@/types';
import { formatDate } from '@/utils';
import { Head, router } from '@inertiajs/vue3';


type Props = {
    users: ModelPaginationType<User & { reports_count: number }>;
};
defineProps<Props>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
    },
];



/**
 * Handle pagination changes, preserving the current filters.
 */
const handlePageChange = (page: number) => {
    const queryParams = {
        page
    };
    router.get(
        route('users.index'),
        queryParams,
        {
            preserveState: true,
        },
    );
};


</script>



<template>

    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">Users</h1>
                <p class="text-muted-foreground">Manage and review all users</p>
            </div>

            <Card>
                <CardContent class="p-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Display Name</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Reputation</TableHead>
                                <TableHead>Reports</TableHead>
                                <TableHead>Created</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users.data" :key="user.id">
                                <TableCell class="font-medium">#{{ user.id }}</TableCell>
                                <TableCell>
                                    {{ user.name }}
                                </TableCell>
                                <TableCell>
                                    <Badge variant="secondary" class="capitalize"
                                        :class="{ 'bg-green-100 text-green-600': user.is_active, 'bg-red-100 text-red-600': !user.is_active }">
                                        {{ user.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </TableCell>
                                <TableCell>


                                    {{ user.reputation }}



                                </TableCell>
                                <TableCell>
                                    {{ user.reports_count }}
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ formatDate(user.created_at) }}
                                </TableCell>
                                <TableCell>


                                </TableCell>
                            </TableRow>

                        </TableBody>
                    </Table>
                    <ModelPagination :model="users" @update:page="handlePageChange" />
                </CardContent>
            </Card>


        </div>
    </AppLayout>
</template>