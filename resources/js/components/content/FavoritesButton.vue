<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import 'vue-toast-notification/dist/theme-sugar.css';
import { Content, ContentEntity } from '@/types';
import { Ref, ref } from 'vue';

interface Props {
    content: Content
    contentEntity: ContentEntity
}

const emit = defineEmits(['favorited', 'unfavorited'])

const props = withDefaults(defineProps<Props>(), {
});

const isFavorite: Ref<boolean> = ref(props.content.is_favorite);

function toggleFavorite(event: MouseEvent) {
    event.preventDefault();
    axios.post('/content/favorite', {
        id: props.content.id,
        contentEntity: props.contentEntity
    }).then (response => {
        isFavorite.value = !isFavorite.value
        emit(isFavorite.value ? 'favorited' : 'unfavorited', { content: props.content, contentEntity: props.contentEntity})
    });
}
</script>

<template>
    <button
        @click.prevent="toggleFavorite"
        class="w-8 h-8 flex cursor-pointer items-center justify-center rounded-full
           bg-white/80 dark:bg-gray-800/80 shadow transition-colors
           hover:text-yellow-500 overflow-hidden"
    >
        <transition name="heart-toggle" mode="out-in">
            <svg
                v-if="isFavorite"
                key="filled"
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-5 h-5 text-red-500 dark:text-red-400"
            >
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42
                 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81
                 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4
                 6.86-8.55 11.54L12 21.35z"/>
            </svg>

            <svg
                v-else
                key="outline"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                class="w-5 h-5 text-gray-500 dark:text-gray-400"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5
                 4.5 0 116.364 6.364L12 21.364 4.318 12.682a4.5 4.5
                 0 010-6.364z"/>
            </svg>
        </transition>
    </button>
</template>

<style scoped>
.heart-toggle-enter-active,
.heart-toggle-leave-active {
    transition: all 200ms ease;
}
.heart-toggle-enter-from,
.heart-toggle-leave-to {
    transform: scale(0.75);
    opacity: 0;
}
.heart-toggle-enter-to,
.heart-toggle-leave-from {
    transform: scale(1);
    opacity: 1;
}
</style>



