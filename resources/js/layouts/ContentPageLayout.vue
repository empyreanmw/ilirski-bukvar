<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItemType, Category, Content, downloadFinishedPayload, Link, type Tab as TabType} from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n'
import Tabs from '@/components/ui/tab/Tabs.vue';
import Tab from '@/components/ui/tab/Tab.vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import Book from '@/components/content/Book.vue';
import Series from '@/components/content/Series.vue';
import { emitter } from '@/utils/eventBus';
import Icon from '@/components/Icon.vue';
import Pagination from '@/components/content/Pagination.vue';
import NoContent from '@/components/content/NoContent.vue';
import axios from 'axios';
import { useAppMode } from '@/composables/useAppMode';
import SpinningLoader from '@/components/SpinningLoader.vue';

const { isAppOnline } = useAppMode()

interface Props {
    bookSeries: {
        data: Content[];
        links: Link[];
        page: number;
    };
    videoSeries: {
        data: Content[];
        links: Link[];
        page: number;
    };
    category: Category,
}
interface ParentProps {
    title: string
    breadcrumbs: BreadcrumbItemType[]
    tabs: Array<TabType>
}

const isLoading = ref(true)
const parentProps = defineProps<ParentProps>();
const { t } = useI18n()
const page = usePage<Props>();
const bookSeries = ref<Content[]>(page.props.bookSeries?.data);
const videoSeries = page.props.videoSeries;
const activeItem = computed(() => page.props.activeItem)
const category = page.props.category
const handleDownloadFinished = (payload: downloadFinishedPayload):void => {
    bookSeries.value = bookSeries.value.map(item =>
        item.id === payload.content.id ? { ...item, ...payload.content } : item
    )
}
const handleDownloadAll = ():void => {
    axios.post('/download-all', {
        categories: [category.id],
        type: 'book'
    })
}
const isTabNeeded = (tab: TabType): boolean => {
    return parentProps.tabs.includes(tab)
}
onMounted(() => {
    setTimeout(() => {
        const el = document.getElementById('book-' + page.props.activeItem);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }, 1200);
    // Simulate async load
    setTimeout(() => {
        isLoading.value = false
    }, 1000) // Adjust to fit your real loading time

    emitter.on('download-finished', handleDownloadFinished)
})
onBeforeUnmount(() => {
    emitter.off('download-finished')
})

</script>

<template>
    <Head :title="t(`${parentProps.title}.title`)"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <SpinningLoader v-if="isLoading"/>
            <Tabs v-show="!isLoading">
                <Tab v-if="isTabNeeded('video')" title="Video" icon="Video" :is-active="false">
                    <Pagination class="mt-6" :link="videoSeries.links"/>
                    <div v-if="videoSeries.data?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div class="flex flex-col" v-for="series in videoSeries.data" :key="series.name">
                            <Series :active="activeItem === series.id" :series="series"/>
                        </div>
                    </div>
                    <!-- Empty state -->
                    <NoContent v-else icon="Video" type="video"/>
                </Tab>
                <Tab v-if="isTabNeeded('book')" title="Book" icon="Book" :is-active="false">
                    <div class="grid gap-4 mt-6 sm:grid-cols-[1fr_auto] items-start">
                    <!-- Pagination -->
                        <div class="flex flex-wrap gap-2 items-center">
                            <Pagination :is-book="true" :link="page.props.bookSeries?.links" />
                        </div>

                        <!-- Download Button -->
                        <button
                            v-if="isAppOnline() && bookSeries?.length"
                            @click="handleDownloadAll"
                            title="Download all"
                            class="group inline-flex items-center gap-2 px-4 py-2 rounded-md border
         border-gray-200 bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700
         dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-blue-300
         transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <Icon
                                name="download"
                                class="w-5 h-5 text-blue-600 group-hover:text-blue-700 dark:text-blue-400 dark:group-hover:text-blue-300 transition"
                            />
                            <span class="text-sm font-medium group-hover:underline">{{ t('general.download_all') }}</span>
                        </button>

                    </div>

                    <div v-if="bookSeries?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div
                            v-for="book in bookSeries"
                            :key="book.title"
                            class="rounded-xl shadow-md transition hover:shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col"
                        >
                            <Book :id="'book-' + book.id" :active="activeItem === book.id" :book="book"/>
                        </div>
                    </div>
                    <!-- Empty state -->
                    <NoContent v-else icon="Book" type="book"/>
                </Tab>
            </Tabs>
        </div>
    </AppLayout>
</template>
