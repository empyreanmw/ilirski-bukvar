<script setup lang="ts">
import { type BreadcrumbItem, Tab } from '@/types';
import { useI18n } from 'vue-i18n'
import ContentPageLayout from '@/layouts/ContentPageLayout.vue';

const { t } = useI18n()
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('general.health'),
        href: '/health',
    },
];
const tabs: Array<Tab> = ['video', 'book']
</script>

<template>
    <ContentPageLayout :tabs="tabs" title="health" :breadcrumbs="breadcrumbs">
    </ContentPageLayout>
</template>
