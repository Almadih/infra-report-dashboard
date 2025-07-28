// src/composables/useAnimateOnScroll.ts
import { useIntersectionObserver } from '@vueuse/core';
import { ref } from 'vue';

export function useAnimateOnScroll(elementRef: any, options = { threshold: 0.1 }) {
    const isVisible = ref(false);

    const {} = useIntersectionObserver(
        elementRef,
        ([{ isIntersecting }]) => {
            if (isIntersecting) {
                isVisible.value = true;
                // Optional: stop observing after it becomes visible to improve performance
                // stop();
            }
        },
        options,
    );

    return { isVisible };
}
