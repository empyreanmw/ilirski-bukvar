<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import 'vue-toast-notification/dist/theme-sugar.css';
import { SidebarTrigger } from '@/components/ui/sidebar';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import SearchBar from '@/components/content/SearchBar.vue';

withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(),{
    breadcrumbs:()=>[]
});

</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <SidebarTrigger class="md:hidden -ml-1 cursor-pointer" />
        <template v-if="breadcrumbs && breadcrumbs.length > 0">
            <Breadcrumbs :breadcrumbs="breadcrumbs" />
        </template>
        <SearchBar class="ml-auto max-w-xs"/>
    </header>
</template>
