<template>
    <transition name="fade">
        <div
            v-if="isOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70 backdrop-blur-sm"
        >
            <div
                class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-xl shadow-xl p-8 w-full max-w-lg mx-4 relative border border-gray-200 dark:border-gray-700"
            >
                <!-- Close Button -->
                <button
                    @click="close"
                    class="cursor-pointer absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition"
                    aria-label="Close modal"
                >
                    ✖
                </button>

                <!-- Header -->
                <h2 class="text-2xl font-semibold mb-4 text-center">
                    {{ t('components.search_modal.title') }}
                </h2>

                <!-- Search -->
                <SearchBar ref="searchBar" />
            </div>
        </div>
    </transition>
</template>

<script setup lang="ts">
import { ref, defineExpose, nextTick, watch } from 'vue'
import SearchBar from '@/components/content/SearchBar.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const isOpen = ref(false)
const searchBar = ref<InstanceType<typeof SearchBar>>()

function open() {
    isOpen.value = true
    nextTick(() => searchBar.value?.focus())
}

function close() {
    isOpen.value = false
}

// Escape key handling
const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        close()
    }
}

watch(isOpen, (newVal) => {
    if (newVal) {
        window.addEventListener('keydown', handleKeyDown)
    } else {
        window.removeEventListener('keydown', handleKeyDown)
    }
})

defineExpose({ open, close })
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
