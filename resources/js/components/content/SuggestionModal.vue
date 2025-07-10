<script setup lang="ts">
import { ref, defineExpose } from 'vue'
import Icon from '@/components/Icon.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()
const visible = ref(false)
const email = ref('')
const suggestion = ref('')

function open() {
    visible.value = true
}

function close() {
    visible.value = false
    email.value = ''
    suggestion.value = ''
}

defineExpose({ open, close })
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-md p-6 px-8 text-center text-gray-800 dark:text-gray-100 space-y-5">
                <button @click="close" class="cursor-pointer absolute top-3 right-4 text-3xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 leading-none">
                    &times;
                </button>

                <div class="flex flex-col items-center gap-2">
                    <Icon name="mail" class="h-10 w-10"/>
                    <h2 class="text-2xl font-semibold tracking-tight">{{t('components.suggestion_modal.title')}}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 max-w-sm">
                       {{t('components.suggestion_modal.description')}}
                    </p>
                    <a href="mailto:empyreamw@gmail.com"
                       class="text-lg font-semibold text-blue-700 dark:text-blue-300 underline underline-offset-2 hover:text-blue-900 dark:hover:text-white transition">
                        empyreanmw@gmail.com
                    </a>
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
