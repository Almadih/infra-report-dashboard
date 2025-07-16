<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'

// UI Components (assuming shadcn-vue setup)
// Note: In a Laravel/Inertia project, the path is often '@/Components/ui/...'
import { Badge } from '@/components/ui/badge'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'

// Icons
import {
    User,
    Shield,
    FileText,
    Flag,
    TrendingUp,
    TrendingDown,
    Calendar, ExternalLink,
    Star
} from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import { BreadcrumbItem, ModelPagination as ModelPaginationType, ReputationHistory, User as UserType } from '@/types'
import { formatDate } from '@/utils'
import ModelPagination from '@/components/ModelPagination.vue'
import { Button } from '@/components/ui/button'

// --- In a real Inertia app, you would receive props from the controller ---
const props = defineProps<{
    user: UserType & { reports_count: number, flagged_reports: number },
    reputationHistory: ModelPaginationType<ReputationHistory>,
}>();




// Helper functions
const getReputationChangeColor = (value: number) => {
    if (value > 0) return 'text-green-600'
    if (value < 0) return 'text-red-600'
    return 'text-muted-foreground'
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
    },
    {
        title: 'User Details',
        href: route('users.show', props.user.id),
    },
];

const handlePageChange = (page: number) => {
    router.get(
        route('users.show', props.user.id),
        { page },
        {
            preserveState: true,
        },
    );
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="container mx-auto p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2">
                    User Details
                </h1>
                <p class="text-muted-foreground">
                    View user profile and reputation history
                </p>
            </div>

            <div class="grid gap-6">
                <!-- User Profile Card -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center justify-between">
                            <div class="flex items-center gap-2 ">
                                <span class="flex gap-1 items-center">
                                    <User class="w-5 h-5" />
                                    User Profile
                                </span>

                            </div>
                            <Button>
                                <Link :href="route('users.reports', user.id)" class="flex items-center gap-2">
                                View Reports
                                <ExternalLink class="w-4 h-4" />
                                </Link>
                            </Button>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-start gap-6">
                            <Avatar class="w-20 h-20">
                                <AvatarFallback class="text-lg">
                                    {{user.name.split(" ").map((n) => n[0]).join("")}}
                                </AvatarFallback>
                            </Avatar>

                            <div class="flex-1 space-y-4">
                                <div>
                                    <h2 class="text-2xl font-semibold">
                                        {{ user.name }}
                                    </h2>
                                    <div class="flex items-center gap-2 mt-2">
                                        <!-- Status Badge Logic -->
                                        <Badge v-if="user.is_active" variant="default"
                                            class="bg-green-100 text-green-800 border-green-200">
                                            <Shield class="w-3 h-3 mr-1" /> Active
                                        </Badge>
                                        <Badge v-else variant="secondary"
                                            class="bg-yellow-100 text-yellow-800 border-yellow-200">
                                            <Shield class="w-3 h-3 mr-1" /> Inactive
                                        </Badge>

                                        <!-- Reputation Level Badge Logic -->
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
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center p-3 bg-muted rounded-lg">
                                        <div class="flex items-center justify-center mb-1">
                                            <FileText class="w-4 h-4 text-blue-600" />
                                        </div>
                                        <div class="text-2xl font-bold">
                                            {{ user.reports_count }}
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            Reports Submitted
                                        </div>
                                    </div>

                                    <div class="text-center p-3 bg-muted rounded-lg">
                                        <div class="flex items-center justify-center mb-1">
                                            <Flag class="w-4 h-4 text-red-600" />
                                        </div>
                                        <div class="text-2xl font-bold">
                                            {{ user.flagged_reports }}
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            Flagged Reports
                                        </div>
                                    </div>

                                    <div class="text-center p-3 bg-muted rounded-lg">
                                        <div class="flex items-center justify-center mb-1">
                                            <TrendingUp class="w-4 h-4 text-green-600" />
                                        </div>
                                        <div class="text-2xl font-bold">
                                            {{ user.reputation }}
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            Reputation
                                        </div>
                                    </div>

                                    <div class="text-center p-3 bg-muted rounded-lg">
                                        <div class="flex items-center justify-center mb-1">
                                            <Calendar class="w-4 h-4 text-purple-600" />
                                        </div>
                                        <div class="text-sm font-bold">
                                            {{ formatDate(user.created_at) }}
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            Member Since
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Reputation History</CardTitle>
                        <CardDescription>Complete history of reputation changes for this user</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Report ID</TableHead>
                                    <TableHead>Type</TableHead>
                                    <TableHead>Value</TableHead>
                                    <TableHead>Date</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="entry in reputationHistory.data" :key="entry.id">
                                    <TableCell>
                                        <div class="flex items-center gap-2">
                                            <code
                                                class="text-xs bg-muted px-2 py-1 rounded">{{ entry.report_id }}</code>
                                            <Link :href="route('reports.show', entry.report_id)"
                                                class="text-blue-600 hover:text-blue-800">
                                            <ExternalLink class="w-3 h-3" />
                                            </Link>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant="outline" class="text-xs capitalize">
                                            {{ entry.type.replace('_', ' ') }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <div class="flex items-center gap-1 font-medium"
                                            :class="getReputationChangeColor(entry.amount)">
                                            <TrendingUp v-if="entry.amount > 0" class="w-4 h-4 text-green-600" />
                                            <TrendingDown v-else-if="entry.amount < 0" class="w-4 h-4 text-red-600" />
                                            {{ entry.amount > 0 ? "+" : "" }}{{ entry.amount }}
                                        </div>
                                    </TableCell>
                                    <TableCell class="text-sm text-muted-foreground">
                                        {{ formatDate(entry.created_at) }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                <div v-if="reputationHistory.data.length === 0" class="text-center py-8">
                    <p class="text-muted-foreground">
                        No reputation history found matching your criteria.
                    </p>
                </div>

                <div v-else>
                    <ModelPagination :model="reputationHistory" @update:page="handlePageChange" />
                </div>
            </div>
        </div>
    </AppLayout>

</template>