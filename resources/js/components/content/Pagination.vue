<script setup lang="ts">
import 'vue-toast-notification/dist/theme-sugar.css';
import { Link } from '@/types';
import { router } from '@inertiajs/vue3';

interface Props {
    link: Link[],
    isBook?: boolean
}
const props = withDefaults(defineProps<Props>(), {
    isBook: false
});

const goTo = (url: string | null):void => {
    if (url) {
        const href = props.isBook ? url + '&tab=book' : url
        router.visit(href, {
            preserveState: false,
            preserveScroll: true,
        })
    }
}

</script>

<template>
    <div v-if="props.link?.length > 3" class="flex gap-2 mt-4">
        <button
            v-for="(link, index) in props.link"
            :key="index"
            class="px-3 py-1 rounded border"
            :class="{
                        'bg-blue-600 text-white': link.active,
                        'hover:bg-gray-200': !link.active
                    }"
            :disabled="!link.url"
            @click="goTo(link.url)"
            v-html="link.label"
        />
    </div>
</template>
