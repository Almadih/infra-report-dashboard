<script setup lang="ts">
import ModelPagination from '@/components/ModelPagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { DropdownMenu } from '@/components/ui/dropdown-menu';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, ModelPagination as ModelPaginationType, User } from '@/types';
import { formatDate } from '@/utils';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Eye, MoreHorizontal, Star, UserCheck, UserX } from 'lucide-vue-next';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog"
import { ref } from 'vue';


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

const selectedUser = ref<User | null>(null);
const showDeactivateUserModal = ref(false)
const showActivateUserModal = ref(false)



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

const form = useForm<{ is_active: boolean }>({
    is_active: true
})


const submit = (isActive: boolean) => {
    if (!selectedUser.value) return
    form.is_active = isActive

    form.put(route('users.status', selectedUser.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeactivateUserModal.value = false
            showActivateUserModal.value = false
            selectedUser.value = null
        }
    });
}


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
                                <TableHead>Title</TableHead>
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
                                    <Badge v-if="user.reputation_title === 'Expert'" variant="default"
                                        class="bg-purple-100 text-purple-800 border-purple-200">
                                        <Star class="w-3 h-3 mr-1" /> Expert
                                    </Badge>
                                    <Badge v-else-if="user.reputation_title === 'Trusted'" variant="default"
                                        class="bg-blue-100 text-blue-800 border-blue-200">
                                        <Star class="w-3 h-3 mr-1" /> Trusted
                                    </Badge>
                                    <Badge v-else-if="user.reputation_title === 'Regular'" variant="secondary">
                                        <Star class="w-3 h-3 mr-1" /> Regular
                                    </Badge>
                                    <Badge v-else-if="user.reputation_title === 'Newcomer'" variant="outline">
                                        <Star class="w-3 h-3 mr-1" /> New
                                    </Badge>
                                    <Badge v-else variant="outline">
                                        <Star class="w-3 h-3 mr-1" /> Unknown
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    {{ user.reports_count }}
                                </TableCell>
                                <TableCell class="text-sm text-muted-foreground">{{ formatDate(user.created_at) }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" class="h-8 w-8 p-0">
                                                <MoreHorizontal class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('users.show', user.id)"
                                                    class="flex items-center cursor-pointer">
                                                <Eye class="mr-2 h-4 w-4" />
                                                <span>View Details</span>
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem class="cursor-pointer" v-if="user.is_active" @click="() => {
                                                showDeactivateUserModal = true
                                                selectedUser = user
                                            }">
                                                <UserX class="mr-2 h-4 w-4 text-red-800" />
                                                <span class="text-red-800">Deactivate User</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem class="cursor-pointer text-green-800" v-else @click="() => {
                                                showActivateUserModal = true
                                                selectedUser = user
                                            }">
                                                <UserCheck class="mr-2 h-4 w-4 text-green-800" />
                                                <span class="text-green-800">Activate User</span>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>

                        </TableBody>
                    </Table>
                    <AlertDialog v-model:open="showDeactivateUserModal">
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Deactivate User {{ selectedUser?.name }}</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Are you sure you want to deactivate this user?
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel :disabled="form.processing">Cancel
                                </AlertDialogCancel>
                                <AlertDialogAction @click="() => submit(false)" :disabled="form.processing"
                                    class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
                                    {{
                                        form.processing ? "Deactivating..." : "Deactivate User"
                                    }}
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>

                    <AlertDialog v-model:open="showActivateUserModal">
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Activate User {{ selectedUser?.name }}</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Are you sure you want to activate this user?
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel :disabled="form.processing">Cancel
                                </AlertDialogCancel>
                                <AlertDialogAction @click="() => submit(true)" :disabled="form.processing">
                                    {{
                                        form.processing ? "Activating..." : "Activate User"
                                    }}
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                    <ModelPagination :model="users" @update:page="handlePageChange" />
                </CardContent>
            </Card>


        </div>
    </AppLayout>
</template>