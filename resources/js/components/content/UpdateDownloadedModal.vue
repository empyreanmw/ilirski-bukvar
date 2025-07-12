<script setup lang="ts">
import { ref, defineExpose } from 'vue'
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import axios from 'axios';

const { t } = useI18n()
const visible = ref(false)
const email = ref('')
const suggestion = ref('')
const open = () => {
    visible.value = true
}
const close = () => {
    visible.value = false
    email.value = ''
    suggestion.value = ''
}
const handleQuitAndUpdate = () => {
    axios.get('/quit-and-update')
}

defineExpose({ open, close })
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-900 rounded-lg p-6 max-w-sm w-full shadow-lg space-y-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{t('components.update_downloaded.title')}}</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{t('components.update_downloaded.description')}}</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{t('components.update_downloaded.description_one')}}</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{t('components.update_downloaded.description_two')}}</p>

                <div class="flex justify-end gap-2 mt-6">
                    <Button class="cursor-pointer" variant="ghost" @click="close()">{{t('general.form.cancel')}}</Button>
                    <Button
                        @click="handleQuitAndUpdate"
                        class="cursor-pointer"
                    >
                        {{t('components.update_downloaded.install')}}
                    </Button>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
