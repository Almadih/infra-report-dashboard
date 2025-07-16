<script setup lang="ts">
import { ModelPagination } from '@/types';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';

type Props = {
    model: ModelPagination<any>;
};

const emits = defineEmits<{
    (e: 'update:page', page: number): void;
}>();
defineProps<Props>();

const handlePageChange = (page: number) => {
    emits('update:page', page);
}

</script>



<template>
    <div>

        <div v-if="model.data.length === 0" class="text-center py-8">
            <p class="text-muted-foreground">
                No results found matching your criteria.
            </p>
        </div>

        <Pagination v-else v-slot="{ page }" :items-per-page="model.per_page" :total="model.total" :default-page="1"
            :siblingCount="1" @update:page="handlePageChange">
            <PaginationContent v-slot="{ items }">
                <PaginationPrevious />

                <template v-for="(item, index) in items" :key="index">
                    <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page">
                        {{ item.value }}
                    </PaginationItem>
                </template>

                <PaginationEllipsis :index="10" />

                <PaginationNext />
            </PaginationContent>
        </Pagination>

        <div class="mt-4 text-sm text-gray-600 text-center">
            Showing {{ model.from }} to {{ model.to }} of {{ model.total }} results
        </div>
    </div>
</template>