<!-- src/components/SearchModal.vue -->
<template>
    <transition name="fade">
        <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-lg mx-4 relative">
                <button @click="close" class="cursor-pointer absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    ✖
                </button>
                <h2 class="text-2xl font-semibold mb-4">Search</h2>
                <SearchBar ref="searchBar"/>
            </div>
        </div>
    </transition>
</template>

<script setup lang="ts">
import { ref, defineExpose, nextTick, watch } from 'vue';
import SearchBar from '@/components/content/SearchBar.vue';

const isOpen = ref(false);
const searchBar = ref<InstanceType<typeof SearchBar>>();

function open() {
    isOpen.value = true;
    nextTick(() => searchBar.value?.focus());
}

function close() {
    isOpen.value = false;
}

// Close modal on Escape key
const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        close();
    }
};

watch(isOpen, (newVal) => {
    if (newVal) {
        window.addEventListener('keydown', handleKeyDown);
    } else {
        window.removeEventListener('keydown', handleKeyDown);
    }
});

// Allow parent to call `open()`
defineExpose({ open, close });
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
