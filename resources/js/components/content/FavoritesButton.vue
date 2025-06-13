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
    <!-- Favorite -->
    <button
        @click.prevent="toggleFavorite"
        class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full bg-white/80 dark:bg-gray-800/80 shadow hover:text-yellow-500 transition"
    >
        <Icon :name="isFavorite ? 'heart' : 'heartOff'" class="w-5 h-5 text-red-500" />
    </button>
</template>
