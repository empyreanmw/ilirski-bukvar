q<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import OfflineModeNotification from '@/components/content/OfflineModeNotification.vue';
import ContentDownloadBar from '@/components/content/ContentDownloadBar.vue';
import { onMounted, onUnmounted, ref } from 'vue';
import SearchModal from '@/components/content/SearchModal.vue';
import SuggestionModal from '@/components/content/SuggestionModal.vue';
import { emitter } from '@/utils/eventBus';
import DownloadPathModal from '@/components/content/DownloadPathModal.vue';
import { usePage } from '@inertiajs/vue3';
import UpdateDownloadedModal from '@/components/content/UpdateDownloadedModal.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const page = usePage()
const handleKeyDown = (event: KeyboardEvent) => {
    // Only open modal if no input/textarea is already focused
    const tagName = (event.target as HTMLElement).tagName.toLowerCase();
    if (tagName === 'input' || tagName === 'textarea') return;

    if (event.key === 's' || event.key === 'S') {
        event.preventDefault(); // Block default only if not focused
        setTimeout(() => {
            searchModalRef.value?.open();
        }, 0);
    } else if (event.key === 'Escape') {
        searchModalRef.value?.close();
    }
};
const suggestionModal = ref(null)
const downloadPathModal = ref(null)
const updateModal = ref(null)
const handleSuggestionClicked = () => {
    suggestionModal.value?.open()
}
const handleUpdateDownloaded = () => {
    updateModal.value?.open()
}
const searchModalRef = ref<InstanceType<typeof SearchModal> | null>(null);
onMounted(() => {
    if (page.props.downloadPathMissing) {
        downloadPathModal.value?.open()
    }
    window.addEventListener('keydown', handleKeyDown);

    emitter.on('update-downloaded', handleUpdateDownloaded)
    emitter.on('suggestion-clicked', handleSuggestionClicked)
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
        <DownloadPathModal ref="downloadPathModal" v-if="page.props.downloadPathMissing" />
        <ContentDownloadBar/>
        <SuggestionModal ref="suggestionModal"/>
        <UpdateDownloadedModal ref="updateModal"/>
        <OfflineModeNotification/>
        <SearchModal ref="searchModalRef" />
        <slot />
    </AppLayout>
</template>
