<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import 'vue-toast-notification/dist/theme-sugar.css';
import { Content, ContentEntity } from '@/types';

interface Props {
    content: Content,
    contentEntity: ContentEntity
}

const props = withDefaults(defineProps<Props>(), {
});

function showInFolder(event: MouseEvent) {
    event.preventDefault();
    axios.post('/show-in-folder', {
        id: props.content.id,
        contentEntity: props.contentEntity
    }).then (response => {
    });
}
</script>

<template>
    <button
        @click.prevent="showInFolder"
        class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full bg-white/80 dark:bg-gray-800/80 shadow hover:text-yellow-500 transition"
    >
        <Icon name="folder" class="h-5 w-5 text-gray-700 hover:text-green-500 dark:text-gray-300" />
    </button>
</template>
