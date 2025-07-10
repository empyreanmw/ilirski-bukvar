<script setup lang="ts">
import { type BreadcrumbItem, Tab } from '@/types';
import { useI18n } from 'vue-i18n'
import ContentPageLayout from '@/layouts/ContentPageLayout.vue';

const { t } = useI18n()
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('general.history'),
        href: '/history',
    },
];
const tabs: Array<Tab> = ['video', 'book']
</script>

<template>
    <ContentPageLayout :tabs="tabs" title="history" :breadcrumbs="breadcrumbs">
    </ContentPageLayout>
</template>
