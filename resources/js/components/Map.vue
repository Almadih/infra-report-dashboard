<script setup lang="ts">
import { ref, watch } from 'vue';

type props = {
    center: {
        lat: number;
        lng: number;
    };
    zoom: number;
    libraries?: any[];
    classes?: string;
};

const heatmapData = ref([]);
const map = ref(null);

defineProps<props>();
const emit = defineEmits(['map:loaded']);

watch(map, (newMap) => {
    if (newMap) {
        emit('map:loaded', newMap);
    }
});
</script>

<template>
    <GMapMap ref="map" :class="classes" map-id="satellite" nonce="test" :center="center" style="width: 100%; height: 100%" :zoom="zoom">
        <slot />
        <GMapHeatmap :data="heatmapData"></GMapHeatmap>
    </GMapMap>
</template>
