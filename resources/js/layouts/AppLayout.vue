<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import OfflineModeNotification from '@/components/content/OfflineModeNotification.vue';
import ContentDownloadBar from '@/components/content/ContentDownloadBar.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import SearchModal from '@/components/content/SearchModal.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const handleKeyDown = (event: KeyboardEvent) => {
    // Only open modal if no input/textarea is already focused
    const tagName = (event.target as HTMLElement).tagName.toLowerCase();
    if (tagName === 'input' || tagName === 'textarea') return;

    if (event.key === 's' || event.key === 'S') {
        event.preventDefault(); // Block default only if not focused
        setTimeout(() => {
            searchModalRef.value?.open();
        }, 0); // 🔥 wait for next tick/microtask
    } else if (event.key === 'Escape') {
        searchModalRef.value?.close();
    }
};

const searchModalRef = ref<InstanceType<typeof SearchModal> | null>(null);
onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
});

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <ContentDownloadBar/>
        <OfflineModeNotification/>
        <SearchModal ref="searchModalRef" />
        <slot />
    </AppLayout>
</template>
