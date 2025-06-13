<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Content, Link, UnfavoriteEvent, Series as SeriesType } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n'
import Tabs from '@/components/ui/tab/Tabs.vue';
import Tab from '@/components/ui/tab/Tab.vue';
import { ref } from 'vue';
import Book from '@/components/content/Book.vue';
import Series from '@/components/content/Series.vue';
import Video from '@/components/content/Video.vue';
import Pagination from '@/components/content/Pagination.vue';
import NoContent from '@/components/content/NoContent.vue';

const { t } = useI18n()
interface Props {
    books: {
        data: Content[];
        links: Link[];
        page: number;
    };
    videos: {
        data: Content[];
        links: Link[];
        page: number;
    };
    series: {
        data: SeriesType[];
        links: Link[];
        page: number;
    };
}

const page = usePage<Props>();
const books = ref(page.props.books.data);
const videos = ref(page.props.videos);
const seriesList  = ref(page.props.series.data);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('general.favorites'),
        href: '/favorites',
    },
];
const handleUnfavorited = (event: UnfavoriteEvent): void => {
    switch (event.contentEntity) {
        case "content":
           books.value = books.value.filter(item => item.id !== event.content.id);
            break;
        case "series":
            seriesList.value = seriesList.value.filter(item => item.id !== event.content.id);
            break;
    }
}
</script>

<template>
    <Head :title="t('sidebar.favorites')"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Tabs>
                <Tab :title="t('general.series')" icon="Video" :is-active="false">
                    <div v-if="seriesList?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div
                            v-for="series in seriesList"
                            :key="series.name"
                            class="rounded-xl overflow-hidden shadow-md transition hover:shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col"
                        >
                            <Series @unfavorited="(event) => handleUnfavorited(event)" :series="series"/>
                        </div>
                    </div>
                    <NoContent icon="Video" type="series" v-else/>
                    <Pagination :link="page.props.series.links"/>
                </Tab>
                <Tab :title="t('general.video')" icon="Video" :is-active=false>
                    <div v-if="videos.data?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div class="flex flex-col" v-for="video in videos.data" :key="video.title">
                            <Video @unfavorited="(event) => handleUnfavorited(event)" :video="video"/>
                        </div>
                    </div>
                    <NoContent icon="Video" type="video" v-else/>
                    <Pagination :link="videos.links"/>
                </Tab>
                <Tab :title="t('general.book')" icon="Book" :is-active="false">
                    <div v-if="books.data?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div
                            v-for="book in books"
                            :key="book.title"
                            class="rounded-xl overflow-hidden shadow-md transition hover:shadow-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col"
                        >
                            <Book @unfavorited="(event) => handleUnfavorited(event)" :book="book"/>
                        </div>
                    </div>
                    <NoContent icon="Book" type="book" v-else/>
                    <Pagination :link="page.props.books.links"/>
                </Tab>
            </Tabs>
        </div>
    </AppLayout>
</template>
