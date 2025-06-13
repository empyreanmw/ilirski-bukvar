<script setup lang="ts">

import { ref, useSlots, provide } from 'vue';
import Icon from '@/components/Icon.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const slots = useSlots();
const tabs = slots!.default!()
const tab = page.props.tab;
const activeTab = ref(tab)
const tabClasses = "cursor-pointer inline-flex items-center justify-center p-4 border-b-2 border-transparent active rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group "
const activeTabClasses = "cursor-pointer inline-flex items-center justify-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group"
provide('activeTab', activeTab)

function getTabClasses(tab: string) {
    if (activeTab.value === tab) {
        return activeTabClasses
    }

    return tabClasses
}
</script>

<template>
    <div>
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li v-for="tab in tabs" :key="tab.props!.title" class="me-2"  @click="activeTab = tab.props!.title">
                    <div :class=getTabClasses(tab.props!.title)>
                        <Icon :name="tab.props!.icon"></Icon>
                        <span class="ml-1"> {{tab.props!.title}} </span>
                    </div>
                </li>
            </ul>
        </div>
        <slot></slot>
    </div>
</template>
