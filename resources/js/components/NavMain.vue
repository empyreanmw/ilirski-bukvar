<script setup lang="ts">
import { SidebarGroup, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarMenu>
            <SidebarMenuItem class="p-2" v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child :is-active="item.href === page.url || t('general.' + page.props.category) === item.title || page.url.includes(item.href)"
                    :tooltip="item.title"
                >
                    <Link class="p-2" :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
