// src/composables/useAnimatedCounter.ts
import { useIntersectionObserver } from '@vueuse/core';
import { computed, ref, watch } from 'vue';

export function useAnimatedCounter(targetRef: any, endValue: number, duration = 2000) {
    const count = ref(0);
    const isVisible = ref(false);

    useIntersectionObserver(
        targetRef,
        ([{ isIntersecting }]) => {
            isVisible.value = isIntersecting;
        },
        { threshold: 0.5 },
    );

    watch(isVisible, (newValue) => {
        if (newValue && count.value === 0) {
            // Start animation only if it's visible and hasn't run yet
            let startTimestamp: number | null = null;
            const step = (timestamp: number) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                count.value = Math.floor(progress * endValue);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    });

    return computed(() => count.value);
}
