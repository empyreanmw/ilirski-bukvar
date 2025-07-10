<script setup lang="ts">
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { emitter } from '@/utils/eventBus';

const page = usePage<SharedData>();
interface Props {
    items: NavItem[];
    class?: string;
}

defineProps<Props>();
const handleItemClick = (item: NavItem) => {
    emitter.emit(item.event);
}
</script>

<template>
    <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton class="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100" :is-active="item.href === page.url" as-child>
                        <Link v-if="item.href" :href="item.href" target="_blank" rel="noopener noreferrer">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                        <div v-else @click="handleItemClick(item)" class="cursor-pointer">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </div>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
