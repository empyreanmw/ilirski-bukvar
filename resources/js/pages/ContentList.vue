<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type Series, BreadcrumbItem, Content, downloadFinishedPayload, Link } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n'
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import Video from '@/components/content/Video.vue';
import Pagination from '@/components/content/Pagination.vue';
import { emitter } from '@/utils/eventBus';
import NoContent from '@/components/content/NoContent.vue';
import SpinningLoader from '@/components/SpinningLoader.vue';

interface Props {
    content: {
        data: Content[];
        links: Link[];
        page: number;
    };
    series: Series
}

const { t } = useI18n()
const page = usePage<Props>();
const items = ref<Content[]>(page.props.content.data)
const pagination = computed(() => page.props.content.links)
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t(`general.${page.props.category}`),
        href: `/${page.props.category}`,
    },
    {
        title: page.props.series.name,
        href: `/content/${items.value[0]?.parent.id}`,
    },
];
const isLoading = ref(true)
const activeItem = computed(() => page.props.activeItem)
const handleDownloadFinished = (payload: downloadFinishedPayload) => {
    items.value = items.value.map(item =>
        item.id === payload.content.id ? { ...item, ...payload.content } : item
    )
}
const handleScrollToSearchFile = () => {
    setTimeout(() => {
        const el = document.getElementById('video-' + page.props.activeItem);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }, 1200);
}
const handleLastWatched = (newLastWatchedId: number) => {
  items.value = items.value.map(item => ({
    ...item,
    last_watched: item.id === newLastWatchedId,
    completed: item.completed
  }));
};

const handleCompleted = (completedId: number, value: boolean) => {
  items.value = items.value.map(item => ({
    ...item,
    completed: item.id === completedId ? value : item.completed,
  }));
};

onMounted(() => {
    handleScrollToSearchFile()
    emitter.on('download-finished', handleDownloadFinished)
    // Simulate async load
    setTimeout(() => {
        isLoading.value = false
    }, 1000) // Adjust to fit your real loading time
})
onBeforeUnmount(() => {
    emitter.off('download-finished')
})

</script>

<template>
    <Head :title="t(`general.${page.props.category}`)"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <SpinningLoader v-if="isLoading"/>
            <div v-show="!isLoading">
                <Pagination v-if="items.length" :lastWatchedUrl="page.props.lastWatchedUrl" :link="pagination"></Pagination>
                <div v-if="items.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                    <div
                        v-for="content in items" :key="content.id"
                    >
                        <Video @last-watched="handleLastWatched" @completed="handleCompleted" :id="'video-' + content.id" :active="activeItem === content.id" :video="content"/>
                    </div>
                </div>
                <NoContent v-else icon="Video" type="video"/>
            </div>
        </div>
    </AppLayout>
</template>
