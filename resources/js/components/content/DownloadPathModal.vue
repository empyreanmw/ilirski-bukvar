<script setup lang="ts">
import { ref, defineExpose } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

import Icon from '@/components/Icon.vue'
import Dialog from '@/components/content/Dialog.vue'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import {useToast} from 'vue-toast-notification';

const { t } = useI18n()
const $toast = useToast();
const visible = ref(false)

const form = useForm({
    download_path: ''
})

function open() {
    visible.value = true
}

function close() {
    visible.value = false
    form.reset()
}

function submit() {
    form.put(route('settings.update-download-path'), {
        preserveScroll: true,
        onSuccess: () => {
            $toast.open({
                message: t('settings.downloads.download_path_success'),
                type: 'success',
                duration: 5000
            });
            setTimeout(close, 1500)
        }
    })
}

defineExpose({ open, close })
</script>

<template>
    <transition name="fade">
        <div
            v-if="visible"
            class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 dark:bg-gray-900/80 backdrop-blur-md"
        >
            <transition name="scale-fade">
                <div
                    class="relative w-full max-w-xl rounded-3xl p-10 shadow-xl border space-y-10
                 bg-white/90 text-gray-800 border-gray-200
                 dark:bg-gray-800/90 dark:text-gray-100 dark:border-gray-700"
                >
                    <!-- Header -->
                    <div class="text-center space-y-4">
                        <Icon name="download" class="mx-auto h-12 w-12 text-blue-600 dark:text-blue-400" />
                        <h2 class="text-3xl font-semibold tracking-wide">
                            {{ t('general.download_path_modal.title') }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ t('general.download_path_modal.subtitle') || 'Select a default path for your downloads.' }}
                        </p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="grid gap-6">
                        <Dialog
                            type="folder"
                            :model="form.download_path"
                            @file-selected="val => form.download_path = val"
                        />
                        <InputError :message="form.errors.download_path" class="text-red-500 text-sm mt-1" />

                        <div class="flex justify-center gap-4 items-center mt-4">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="cursor-pointer px-6 py-2 rounded-lg shadow transition-all
                       bg-blue-600 text-white hover:bg-blue-700
                       disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ t('general.form.save') }}
                            </Button>
                            <Transition
                                enter-active-class="transition-opacity duration-300"
                                enter-from-class="opacity-0"
                                leave-active-class="transition-opacity duration-300"
                                leave-to-class="opacity-0"
                            >
                                <p v-show="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400">
                                    {{ t('general.form.saved') }}
                                </p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </transition>
        </div>
    </transition>
</template>


<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.scale-fade-enter-active {
    transition: transform 0.4s ease, opacity 0.3s ease;
}
.scale-fade-enter-from {
    transform: scale(0.92);
    opacity: 0;
}
.scale-fade-leave-active {
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.scale-fade-leave-to {
    transform: scale(0.95);
    opacity: 0;
}
</style>
