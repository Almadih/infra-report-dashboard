<script setup lang="ts">
import { formatDate, severityColors, statusColors } from '@/utils';
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
                    <Badge variant="outline" :class="statusColors[report.status.name as keyof typeof statusColors]">
                        <span class="flex items-center gap-1 capitalize">
                            <component class="h-4 w-4"
                                :is="statusIcons[report.status.name as keyof typeof statusIcons]" />
                            {{ report.status.name.replace('_', ' ') }}
                        </span>
                    </Badge>

                </TableCell>
                <TableCell>
                    <Badge class="capitalize"
                        :class="severityColors[report.severity.name as keyof typeof severityColors]">
                        {{ report.severity.name }}
                    </Badge>
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