<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n'
import ContentPageLayout from '@/layouts/ContentPageLayout.vue';

const { t } = useI18n()
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('general.health'),
        href: '/health',
    },
];
</script>

<template>
    <ContentPageLayout title="health" :breadcrumbs="breadcrumbs">
    </ContentPageLayout>
</template>
