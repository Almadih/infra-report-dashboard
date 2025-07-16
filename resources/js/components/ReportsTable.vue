<script setup lang="ts">
import { formatDate } from '@/utils';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { CheckCircle, Clock, Edit, Eye, MapPin, User } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Report } from '@/types';


type Props = {
    reports: Report[];
};

defineProps<Props>();

const statusIcons = {
    pending: Clock,
    under_review: User,
    verified: Edit,
    resolved: CheckCircle,
};

</script>


<template>

    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>ID</TableHead>
                <TableHead>Address</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Severity</TableHead>
                <TableHead>Damage Type</TableHead>
                <TableHead>Created</TableHead>
                <TableHead class="text-right">Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="report in reports" :key="report.id">
                <TableCell class="font-mono text-xs">
                    <code class="bg-muted px-2 py-1 rounded">{{ report.id }}...</code>
                </TableCell>
                <TableCell>
                    <div class="flex items-start gap-2 max-w-xs">
                        <MapPin class="w-4 h-4 text-muted-foreground mt-0.5 flex-shrink-0" />
                        <span class="text-sm truncate" :title="report.address">
                            {{ report.address }}
                        </span>
                    </div>
                </TableCell>
                <TableCell>
                    <!-- Status Badge Logic -->
                    <Badge v-if="report.status.name === 'pending'" variant="secondary"
                        class="bg-yellow-100 text-yellow-800 border-yellow-200">
                        <component class="h-4 w-4" :is="statusIcons[report.status.name as keyof typeof statusIcons]" />
                        Pending
                    </Badge>
                    <Badge v-else-if="report.status.name === 'under_review'" variant="default"
                        class="bg-blue-100 text-orange-800 border-orange-200">
                        <component class="h-4 w-4" :is="statusIcons[report.status.name as keyof typeof statusIcons]" />
                        Under
                        Review
                    </Badge>
                    <Badge v-else-if="report.status.name === 'resolved'" variant="default"
                        class="bg-green-100 text-green-800 border-green-200">
                        <component class="h-4 w-4" :is="statusIcons[report.status.name as keyof typeof statusIcons]" />
                        Resolved
                    </Badge>
                    <Badge v-else-if="report.status.name === 'verified'" variant="default"
                        class="bg-blue-100 text-blue-800 border-blue-200">
                        <component class="h-4 w-4" :is="statusIcons[report.status.name as keyof typeof statusIcons]" />
                        Verified
                    </Badge>

                </TableCell>
                <TableCell>
                    <!-- Severity Badge Logic -->
                    <Badge v-if="report.severity.name === 'critical'" variant="destructive"
                        class="bg-red-600 text-white">Critical</Badge>
                    <Badge v-else-if="report.severity.name === 'high'" variant="destructive"
                        class="bg-red-100 text-red-800 border-red-200">High</Badge>
                    <Badge v-else-if="report.severity.name === 'medium'" variant="secondary"
                        class="bg-orange-100 text-orange-800 border-orange-200">Medium</Badge>
                    <Badge v-else-if="report.severity.name === 'low'" variant="outline"
                        class="bg-green-100 text-green-800 border-green-200">Low</Badge>
                    <Badge v-else variant="outline">Unknown</Badge>
                </TableCell>
                <TableCell>
                    <Badge variant="outline" class="capitalize">
                        {{ report.damage_type.name.replace('_', ' ') }}
                    </Badge>
                </TableCell>
                <TableCell class="text-sm text-muted-foreground">
                    {{ formatDate(report.created_at) }}
                </TableCell>
                <TableCell class="text-right">
                    <Button>
                        <Link :href="route('reports.show', report.id)" class="flex items-center justify-end">
                        View Details
                        </Link>
                        <Eye class="w-3 h-3" />
                    </Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

</template>