<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Cog, History, Cross, Ambulance,Film, Podcast, Heart, DownloadCloud, ShieldQuestion } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

import { useI18n } from 'vue-i18n'
import NavModeSwitcher from '@/components/NavModeSwitcher.vue';

const { t } = useI18n()

const mainNavItems: NavItem[] = [
    {
        title: t('sidebar.history'),
        href: '/history',
        icon: History,
    },
    {
        title: t('sidebar.theology'),
        href: '/theology',
        icon: Cross,
    },
    {
        title: t('sidebar.health'),
        href: '/health',
        icon: Ambulance,
    },
    {
        title: t('sidebar.movies'),
        href: '/movies',
        icon: Film,
    },
    {
        title: t('sidebar.podcasts'),
        href: '/podcasts',
        icon: Podcast,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: t('sidebar.favorites'),
        href: '/favorites',
        icon: Heart,
    },
    {
        title: t('sidebar.download_manager'),
        href: '/download-manager',
        icon: DownloadCloud,
    },
    {
        title: t('sidebar.settings'),
        href: '/settings',
        icon: Cog,
    },
    {
        title: t('sidebar.suggestions'),
        icon: ShieldQuestion,
        href: '',
        event: 'suggestion-clicked'
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('history')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="mt-5">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavModeSwitcher class="ml-3" />
            <NavFooter :items="footerNavItems" />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
