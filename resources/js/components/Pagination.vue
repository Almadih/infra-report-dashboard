<template>
    <div class="flex justify-between flex-col">
        <div class="flex items-center space-x-2">
            <template v-for="(page, index) in pages" :key="index">
                <template v-if="page === '...'">
                    <span class="px-3 py-2 text-gray-500">...</span>
                </template>
                <span @click="() => handlePageChange(page.label)" v-else :href="page.url"
                    class="px-4 py-2 rounded-lg text-sm font-medium" :class="[
                        page.active
                            ? 'bg-blue-600 text-white hover:bg-blue-700'
                            : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
                    ]">
                    {{ page.label }}
                </span>
            </template>
        </div>
        <div class="text-sm text-gray-600 mt-4">
            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
    route: {
        type: String,
        required: true,
    },
    query: {
        type: Object,
        required: true,
    }
});

const handlePageChange = (page) => {
    router.get(route('reports-map'), { page, ...props.query }, {
        preserveState: true,

    });
};


const pages = computed(() => {
    const currentPage = props.pagination.current_page;
    const lastPage = props.pagination.last_page;
    const delta = 2; // Number of pages to show on each side of current page

    let range = [];
    for (let i = Math.max(2, currentPage - delta); i <= Math.min(lastPage - 1, currentPage + delta); i++) {
        range.push(i);
    }

    if (currentPage - delta > 2) {
        range.unshift('...');
    }
    if (currentPage + delta < lastPage - 1) {
        range.push('...');
    }

    range.unshift(1);
    if (lastPage !== 1) {
        range.push(lastPage);
    }

    return range.map((page) => {
        if (page === '...') {
            return page;
        }

        return {
            label: page,
            url: props.pagination.path + '?page=' + page,
            active: page === currentPage,
        };
    });
});
</script>