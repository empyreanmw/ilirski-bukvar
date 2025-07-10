<script setup lang="ts">
import { useAppMode } from '@/composables/useAppMode';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()
const { isAppModeSwitchManual } = useAppMode()
const notificationVisible = ref(true)
</script>

<template>
    <div v-if="!isAppModeSwitchManual() && notificationVisible" class="m-1 flex items-center justify-between p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
        <div class="flex items-center">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">{{t('components.offline_mode_notification.offline_message')}}</span>
            </div>
        </div>
        <button  @click="notificationVisible = false" type="button" class="cursor-pointer ms-4 -me-2 text-yellow-800 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 dark:hover:bg-gray-700 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l6 6m0-6L4 10" />
            </svg>
        </button>
    </div>
</template>
