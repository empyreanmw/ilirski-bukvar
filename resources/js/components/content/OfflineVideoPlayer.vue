<script setup lang="ts">
import 'vue-toast-notification/dist/theme-sugar.css';
import { Content } from '@/types';
import { useForm } from '@inertiajs/vue3';

interface Props {
    video: Content;
}
const props = withDefaults(defineProps<Props>(), {});
const openFile = (id: number): void => {
    const form = useForm({
        contentId: id,
    });

    form.post('/open-file', {
        preserveScroll: true,
        onSuccess: () => form.reset('contentId'),
    });
}
</script>

<template>
    <div class="relative w-full max-w-md cursor-pointer group" @click="openFile(video.id)">
        <!-- Image -->
        <img
            :src="props.video.thumbnail_url"
            alt="Video Thumbnail"
            class="w-full h-auto object-cover rounded-lg"
        />

        <!-- Play Button -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div
                class="bg-white/80 dark:bg-gray-900/70 p-3 rounded-full transition group-hover:scale-110"
            >
                <svg
                    class="w-8 h-8 text-black-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path d="M6.5 5.5v9l7-4.5-7-4.5Z" />
                </svg>
            </div>
        </div>
    </div>
</template>
