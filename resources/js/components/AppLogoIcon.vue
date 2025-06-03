<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div class="flex items-center space-x-2">
        <img src="/logo.png" class="h-11 w-auto" alt="infra report logo" />
    </div>
</template>
