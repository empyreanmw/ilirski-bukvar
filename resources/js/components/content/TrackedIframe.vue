<script setup lang="ts">
import { ref, onMounted } from 'vue';

const props = defineProps<{
    src: string;
}>();

const iframeRef = ref<HTMLIFrameElement | null>(null);
const isLoaded = ref(false);

onMounted(() => {
    if (iframeRef.value) {
        // Try to listen to load (may not fire for Google Drive)
        iframeRef.value.addEventListener('load', () => {
            isLoaded.value = true;
        });

        // Fallback: if no load fires after 3s, assume it's ready
        setTimeout(() => {
            if (!isLoaded.value) {
                isLoaded.value = true;
            }
        }, 5000);
    }
});
</script>

<template>
    <div class="relative w-full h-full aspect-video">
        <div v-if="!isLoaded" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-900 z-10">
            <div class="w-8 h-8 border-4 border-grey-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <iframe
            v-show="isLoaded"
            ref="iframeRef"
            :src="props.src"
            class="w-full h-full border rounded"
        ></iframe>
    </div>
</template>
