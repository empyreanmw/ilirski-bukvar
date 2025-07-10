<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import { nextTick, ref } from 'vue';
import type { Content } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()
const searchTerm = ref('')
const searchResults = ref<Content[]>([])
const inputRef = ref<HTMLInputElement | null>(null);
const handleSearch = (): void => {
    if (searchTerm.value.length < 3) return
    axios.post('/search', {
        searchTerm: searchTerm.value
    }).then(response => {
        searchResults.value = response.data.searchResult
    })
}
const focus = () => {
    nextTick(()=> {
        inputRef.value?.focus();
    })
}
defineExpose({ focus })
</script>

<template>
    <div class="relative z-50 w-full">
        <!-- Search Input -->
        <label for="default-search" class="sr-only">Search</label>
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <Icon class="w-4 h-4 text-gray-500 dark:text-gray-400" name="search"/>
        </div>
        <input
            v-model="searchTerm"
            type="search"
            id="default-search"
            class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            :placeholder="t('components.search_bar.placeholder')"
            required
            @input="handleSearch()"
            ref="inputRef"
        />

        <!-- Dropdown -->
        <ul v-if="searchTerm.length >= 3" class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto">
            <li v-if="searchResults.length === 0" class="p-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                {{t('components.search_bar.no_results')}} "<strong>{{ searchTerm }}</strong>"
            </li>
            <li
                v-for="item in searchResults"
                :key="item.id"
                class="flex items-start gap-3 p-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
            >
                <img :src="item.thumbnail" alt="" class="w-12 h-12 object-cover rounded-md" />
                <Link  class="flex flex-col" :href="item.href">
                    <span class="font-medium text-sm text-gray-900 dark:text-white">{{ item.title }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ item.parentType !== 'App\\Models\\Series' ? t('general.' + item.category.toLowerCase()) : item.category }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ t('general.' + item.type.toLowerCase()) }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>
