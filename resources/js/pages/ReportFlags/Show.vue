<script setup lang="ts">
import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/components/ui/alert-dialog"
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogClose
} from "@/components/ui/dialog"
import { CheckCircle, Clock, Bot, User, ExternalLink, Trash2, Shield } from "lucide-vue-next"
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/utils';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { BreadcrumbItem, ReportFlag } from "@/types"


type props = {
    flag: ReportFlag;
};

const props = defineProps<props>();
console.log(props)
const showConfirmReportFlagModal = ref(false)
const deleteForm = useForm({})
const confirmForm = useForm({
    reason: props.flag.reason,
    confirmed_by_admin: true
})
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports',
        href: route('reports.index'),
    },
    {
        title: 'Report Flag Details',
        href: route('report-flags.show', props.flag.id),
    },
];

const handleDeleteFlag = () => {
    deleteForm.delete(route('report-flags.destroy', props.flag.id))
}

const handleConfirmFlag = () => {
    confirmForm.put(route('report-flags.update', props.flag.id), {
        onSuccess: () => showConfirmReportFlagModal.value = false
    })
}

</script>



<template>

    <Head title="Report Flag details" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="grid grid-cols-2">

                <div class="mb-6">
                    <h1 class="text-3xl font-bold mb-2">Report Flag Details</h1>
                    <p class="text-muted-foreground">Review and manage flagged report #{{ flag.id }}</p>
                </div>

                <div class="ml-auto">


                    <div class="flex gap-3">
                        <Dialog v-bind:open="showConfirmReportFlagModal"
                            @update:open="(value) => showConfirmReportFlagModal = value">
                            <DialogTrigger asChild>
                                <Button class="flex items-center gap-2" :disabled="flag.confirmed_by_admin">
                                    <CheckCircle class="w-4 h-4" />
                                    Confirm Flag
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="sm:max-w-[425px]">
                                <DialogHeader>
                                    <DialogTitle>Confirm Flag</DialogTitle>
                                    <DialogDescription>
                                        Confirm this flag and optionally update the reason. This action will
                                        mark
                                        the flag as reviewed
                                        and confirmed.
                                    </DialogDescription>
                                </DialogHeader>
                                <div class="grid gap-4 py-4">
                                    <div class="space-y-2">
                                        <Label htmlFor="reason">Reason</Label>
                                        <Textarea id="reason" v-model:model-value="confirmForm.reason"
                                            placeholder="Enter reason for confirming this flag..."
                                            class="min-h-[100px]" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <DialogClose asChild>

                                        <Button variant="outline">
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Button @click="handleConfirmFlag" :disabled="confirmForm.processing">
                                        {{ confirmForm.processing ? "Confirming..." : "Confirm Flag" }}
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <AlertDialog>
                            <AlertDialogTrigger asChild>
                                <Button variant="destructive" class="flex items-center gap-2">
                                    <Trash2 class="w-4 h-4" />
                                    Delete Flag
                                </Button>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                                    <AlertDialogDescription>
                                        This action cannot be undone. This will permanently delete the flag
                                        record
                                        and remove it from
                                        the system.
                                    </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                    <AlertDialogCancel :disabled="deleteForm.processing">Cancel
                                    </AlertDialogCancel>
                                    <AlertDialogAction @click="handleDeleteFlag" :disabled="deleteForm.processing"
                                        class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
                                        {{ deleteForm.processing ? "Deleting..." : "Delete Flag" }}
                                    </AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">

                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle class="flex items-center gap-2">
                                <Shield class="w-5 h-5" />
                                Flag Status
                            </CardTitle>
                            <Badge v-if="flag.confirmed_by_admin" variant="default"
                                class="bg-green-100 text-green-800 border-green-200">
                                <CheckCircle class="w-3 h-3 mr-1" />
                                Confirmed by Admin
                            </Badge>
                            <Badge v-else variant="secondary" class="bg-yellow-100 text-yellow-800 border-yellow-200">
                                <Clock class="w-3 h-3 mr-1" />
                                Pending Review
                            </Badge>

                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Flag Type</Label>
                                <p class="text-sm font-medium capitalize">{{ flag.type }}</p>
                            </div>
                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Flagged By</Label>
                                <div class="mt-1">


                                    <div v-if="flag.auto_flagged"
                                        class="flex items-center text-sm text-muted-foreground">
                                        <Bot class="w-4 h-4 mr-1" />
                                        Auto - flagged by system
                                    </div>

                                    <div v-else class="flex items-center text-sm text-muted-foreground">
                                        <User class="w-4 h-4 mr-1" />
                                        Manually flagged
                                    </div>


                                </div>
                            </div>
                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Created</Label>
                                <p class="text-sm">{{ formatDate(flag.created_at) }}</p>
                            </div>
                            <div>
                                <Label class="text-sm font-medium text-muted-foreground">Last Updated</Label>
                                <p class="text-sm">{{ formatDate(flag.updated_at) }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>






                <Card>
                    <CardHeader>
                        <CardTitle>Report Information</CardTitle>
                        <CardDescription>Details about the flagged report and related reports</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Report ID</Label>
                            <div class="flex items-center gap-2 mt-1">
                                <code class="text-sm bg-muted px-2 py-1 rounded">{{ flag.report_id }}</code>
                                <Link :href="route('reports.show', flag.report_id)"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <ExternalLink class="w-3 h-3" />
                                View Report
                                </Link>
                            </div>
                        </div>


                        <div v-if="flag.duplicated_report_id">
                            <Label class="text-sm font-medium text-muted-foreground">Duplicate of Report</Label>
                            <div class="flex items-center gap-2 mt-1">
                                <code class="text-sm bg-muted px-2 py-1 rounded">{{ flag.duplicated_report_id }}</code>
                                <Link :href="route('reports.show', flag.duplicated_report_id)"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <ExternalLink class="w-3 h-3" />
                                View Original Report
                                </Link>
                            </div>
                        </div>


                        <div>
                            <Label class="text-sm font-medium text-muted-foreground">Reason</Label>
                            <p class="text-sm mt-1 p-3 bg-muted rounded-md">{{ flag.reason || "No reason provided"
                            }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>

    </AppLayout>
</template>