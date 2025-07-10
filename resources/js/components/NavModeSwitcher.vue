<script setup lang="ts">
import { SidebarMenu, SidebarMenuItem } from '@/components/ui/sidebar';
import { useForm } from '@inertiajs/vue3';
import { useAppMode } from '@/composables/useAppMode';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()
const { getAppMode } = useAppMode()
const switchMode = () => {
    const form = useForm({
        mode: getAppMode() === 'online' ? 'offline' : 'online'
    })
    form.post('/app-mode', {
        preserveState: false
    })
}
</script>
<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <label class="inline-flex items-center cursor-pointer">
                <input
                    type="checkbox"
                    class="sr-only peer"
                    :checked="getAppMode() === 'online'"
                    @click="switchMode()"
                />
                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600 dark:peer-checked:bg-green-600"></div>
                <span
                    :class="['ms-3 text-sm font-medium text-gray-900 dark:text-gray-300', getAppMode() === 'online' ? 'color-success' : 'color-danger']"
                >
                    {{ t('components.nav_mode_switcher.title', {mode: getAppMode()})}}
                </span>
            </label>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
