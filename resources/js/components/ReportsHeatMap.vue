<script lang="ts" setup>
/// <reference types="@types/google.maps" />
import axios from 'axios'
import { onMounted, ref, watch } from 'vue';
import Map from './Map.vue';

const center = ref({ lat: 30.890040983375705, lng: 29.875583732404525 })

const heatmapData = ref([])
const mapRef = ref<google.maps.Map | null>(null)


onMounted(() => {


})

const fetchHeatmapData = () => {
    axios.get(route('reports-heatmap')).then(res => {

        center.value = { lat: res.data.center.coordinates[1], lng: res.data.center.coordinates[0] }
        heatmapData.value = res.data.rows.map((feature: any) => {
            const geometry = JSON.parse(feature.geometry);
            const properties = JSON.parse(feature.properties);
            return { location: new google.maps.LatLng(geometry.coordinates[1], geometry.coordinates[0]), weight: properties.report_count * 10 }
        })
    })
}


const mapLoaded = (map: any) => {
    mapRef.value = map;
}

watch([mapRef], () => {
    if (mapRef.value) {
        fetchHeatmapData();
    }
})

</script>


<template>
    <Map :center="center" :zoom="10" @map:loaded="mapLoaded" class="h-[500px]">
        <GMapHeatmap :data="heatmapData" />
    </Map>
</template>
