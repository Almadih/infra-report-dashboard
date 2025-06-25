<script setup lang="ts">
import { useSidebar } from './ui/sidebar';


const { open: isOpen } = useSidebar()

</script>


<template>
    <div class="flex items-center space-x-2">
        <img src="/logo.webp" :class="{ 'h-11 w-auto': isOpen, 'h-auto w-24': !isOpen }" alt="infra report logo" />
    </div>
</template>
