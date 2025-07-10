<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {
    type BreadcrumbItem,
    Category,
    Content,
    Link
} from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n'
import Tabs from '@/components/ui/tab/Tabs.vue';
import Tab from '@/components/ui/tab/Tab.vue';
import { computed, onMounted, ref } from 'vue';
import Series from '@/components/content/Series.vue';
import Pagination from '@/components/content/Pagination.vue';
import NoContent from '@/components/content/NoContent.vue';
import SpinningLoader from '@/components/SpinningLoader.vue';

interface Props {
    movieSeries: {
        data: Content[];
        links: Link[];
        page: number;
    };
    cartoonSeries: {
        data: Content[];
        links: Link[];
        page: number;
    };
    category: Category
}
const { t } = useI18n()
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('general.movies'),
        href: '/movies',
    },
];

const isLoading = ref(true)
const page = usePage<Props>();
const movieSeries = page.props.movieSeries;
const cartoonSeries = page.props.cartoonSeries;
const activeItem = computed(() => page.props.activeItem)

onMounted(() => {
    setTimeout(() => {
        const el = document.getElementById('book-' + page.props.activeItem);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }, 200);
    setTimeout(() => {
        isLoading.value = false
    }, 1000) // Adjust to fit your real loading time
})

</script>

<template>
    <Head :title="t(`movies.title`)"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <SpinningLoader v-if="isLoading"/>
            <Tabs v-show="!isLoading">
                <Tab title="Movie" icon="Film" :is-active="false">
                    <Pagination :link="movieSeries.links"/>
                    <div v-if="movieSeries.data?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div class="flex flex-col" v-for="series in movieSeries.data" :key="series.name">
                            <Series :with-favorites="false" :limit-height="false" :active="activeItem === series.id" :series="series"/>
                        </div>
                    </div>
                    <!-- Empty state -->
                    <NoContent v-else icon="Film" type="movie"/>
                </Tab>
                <Tab title="Cartoon" icon="Film" :is-active="false">
                    <Pagination :link="cartoonSeries.links"/>
                    <div v-if="cartoonSeries.data?.length" class="grid auto-rows-min gap-4 md:grid-cols-3 pt-4">
                        <div class="flex flex-col" v-for="series in cartoonSeries.data" :key="series.name">
                            <Series :with-favorites="false" :limit-height="false" :active="activeItem === series.id" :series="series"/>
                        </div>
                    </div>
                    <!-- Empty state -->
                    <NoContent v-else icon="Film" type="cartoon"/>
                </Tab>
            </Tabs>
        </div>
    </AppLayout>
</template>
