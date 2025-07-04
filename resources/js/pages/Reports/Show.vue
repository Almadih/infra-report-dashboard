<script setup lang="ts">
import Map from '@/components/Map.vue';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Badge } from '@/components/ui/badge';
import Button from '@/components/ui/button/Button.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Report, Status } from '@/types';
import { formatDate, severityColors, statusColors } from '@/utils';
import { Head, useForm } from '@inertiajs/vue3';
import { Calendar, CheckCircle, Clock, Edit, FileText, MapPin, User } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';

type props = {
    report: Report;
    statuses: Status[];
};

type StatusForm = {
    status_id: string;
};
const props = defineProps<props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Report Details',
        href: '/reports-map',
    },
];

const statuses = ref<Status[]>([]);
const showStatusUpdateModal = ref(false);
const showReportUpdatesModal = ref(false);

const form = useForm<StatusForm>({
    status_id: '',
});

const reportUpdateForm = useForm({
    text: ''
});

const statusIcons = {
    pending: Clock,
    under_review: User,
    verified: Edit,
    resolved: CheckCircle,
};

const filterStatuses = (targetStatus: string) => {
    return props.statuses.filter((status: Status) => status.name.toLowerCase() === targetStatus);
};

const updateAvailableStatuses = (newProps: props) => {
    const reportStatus = newProps.report.status.name.toLocaleLowerCase();
    switch (reportStatus) {
        case 'pending':
            statuses.value = filterStatuses('under_review');
            break;
        case 'under_review':
            statuses.value = filterStatuses('verified');
            break;
        case 'verified':
            statuses.value = filterStatuses('resolved');
            break;
        default:
            statuses.value = [];
            break;
    }
};

onMounted(() => {
    updateAvailableStatuses(props);
});

watch(props, (newProps) => {
    updateAvailableStatuses(newProps);
});

const updateStatus = () => {
    form.put(route('reports.update', props.report.id), {
        onSuccess: () => {
            showStatusUpdateModal.value = false;
            form.reset();
        },
    });
};

const submitReportUpdatesFrom = () => {
    reportUpdateForm.post(route('reports.updates.store', props.report.id), {
        onSuccess: () => { reportUpdateForm.reset(); showReportUpdatesModal.value = false }
    })
}
</script>

<template>

    <Head title="Report Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-h-screen flex-col">
            <main class="flex flex-1 flex-col gap-4 p-4">
                <div class="flex w-full flex-col space-y-6">
                    <div class="grid grid-cols-2">
                        <div>
                            <h1 class="mt-2 text-2xl font-bold tracking-tight">#{{ report.id }}</h1>
                            <div class="text-muted-foreground mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                                <div class="flex items-center gap-1">
                                    <Calendar class="h-4 w-4" />
                                    <span>{{ formatDate(report.created_at) }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <MapPin class="h-4 w-4" />
                                    <span>{{ report.address }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="sticky top-0 z-10 flex h-16 items-center gap-4 bg-background px-4 md:px-6">
                            <div class="ml-auto flex items-center gap-2">
                                <AlertDialog v-model:open="showStatusUpdateModal">
                                    <AlertDialogTrigger as-child>
                                        <Button size="sm" :disabled="report.status.name === 'resolved'">Update
                                            Status</Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent>
                                        <AlertDialogHeader>
                                            <AlertDialogTitle>Update Report Status</AlertDialogTitle>
                                        </AlertDialogHeader>
                                        <div class="w-full">
                                            <Select @update:modelValue="(value) => (form.status_id = value as string)">
                                                <SelectTrigger class="w-full">
                                                    <SelectValue placeholder="Select new status" class="capitalize" />
                                                </SelectTrigger>

                                                <SelectContent>
                                                    <SelectGroup>
                                                        <SelectItem v-for="status in statuses" :key="status.id"
                                                            :value="status.id" class="capitalize">
                                                            {{ status.name.replace('_', ' ') }}
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                                            <Button :disabled="!form.status_id" @click="updateStatus">Update</Button>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Details</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <dl class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-muted-foreground text-sm font-medium">Report ID</dt>
                                        <dd class="text-sm">{{ report.id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-muted-foreground text-sm font-medium">Damage Type</dt>
                                        <dd class="text-sm">{{ report.damage_type.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-muted-foreground text-sm font-medium">Status</dt>
                                        <dd class="text-sm">
                                            <Badge variant="outline"
                                                :class="statusColors[report.status.name as keyof typeof statusColors]">
                                                <span class="flex items-center gap-1 capitalize">
                                                    <component class="h-4 w-4"
                                                        :is="statusIcons[report.status.name as keyof typeof statusIcons]" />
                                                    {{ report.status.name.replace('_', ' ') }}
                                                </span>
                                            </Badge>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-muted-foreground text-sm font-medium">Severity</dt>
                                        <dd class="text-sm">
                                            <Badge class="capitalize"
                                                :class="severityColors[report.severity.name as keyof typeof severityColors]">
                                                {{ report.severity.name }}
                                            </Badge>
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-muted-foreground text-sm font-medium">Address</dt>
                                        <dd class="text-sm">{{ report.address }}</dd>
                                    </div>
                                </dl>
                            </CardContent>
                        </Card>
                        <div class="flex flex-1 flex-col">
                            <Card class="h-full">
                                <CardHeader>
                                    <CardTitle>Description</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p>{{ report.description }}</p>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
                <div class="w-full space-y-6">
                    <Card class="w-full">
                        <CardHeader class="pb-4">
                            <div class="flex items-center gap-2">
                                <FileText class="h-5 w-5 text-muted-foreground" />
                                <CardTitle class="text-xl">Report Updates</CardTitle>
                                <AlertDialog v-model:open="showReportUpdatesModal">
                                    <AlertDialogTrigger as-child class="ml-auto">
                                        <Button size="sm">Add Update</Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent>
                                        <AlertDialogHeader>
                                            <AlertDialogTitle>Add new Update </AlertDialogTitle>
                                        </AlertDialogHeader>
                                        <div class="w-full">
                                            <Textarea v-model:model-value="reportUpdateForm.text"
                                                placeholder="update text" />
                                            <h4 v-if="reportUpdateForm.errors.text">{{ reportUpdateForm.errors.text }}
                                            </h4>
                                        </div>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                                            <Button :disabled="reportUpdateForm.text == ''"
                                                @click="submitReportUpdatesFrom">Submit</Button>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                            </div>
                            <CardDescription>Recent activity and changes</CardDescription>

                        </CardHeader>
                        <CardContent class="space-y-4">

                            <div :key="update.id" v-for="(update, i) in report.updates">
                                <div class="flex items-start gap-3">
                                    <div class="flex-1 space-y-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                                <Clock class="h-3 w-3" />
                                                <span>{{ formatDate(update.created_at) }}</span>
                                            </div>
                                        </div>
                                        <p class="text-sm leading-relaxed">{{ update.text }}</p>
                                    </div>
                                </div>
                                <Separator v-if="i < report.updates.length + 1" class="mt-4" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
                <div class="w-full space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Location</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Map class="h-[500px]" :zoom="9" :center="{
                                lat: report.location.coordinates[1],
                                lng: report.location.coordinates[0],
                            }">
                                <GMapMarker :position="{
                                    lat: report.location.coordinates[1],
                                    lng: report.location.coordinates[0],
                                    zoom: 16,
                                }" />
                            </Map>
                        </CardContent>
                    </Card>
                </div>

                <div class="w-full space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Images</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="flex gap-4">
                                <div v-for="image in report.images" :key="image.id">
                                    <img :src="route('images.show', image.id)" alt="report image"
                                        class="h-100 w-auto" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </main>
        </div>
    </AppLayout>
</template>
