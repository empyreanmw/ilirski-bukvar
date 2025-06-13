<script setup lang="ts">
import { Series } from '@/types';
import DownloadButton from '@/components/content/DownloadButton.vue';
import { Link } from '@inertiajs/vue3';
import FavoritesButton from '@/components/content/FavoritesButton.vue';
import ShowInFolderButton from '@/components/content/ShowInFolderButton.vue';
import { useAppMode } from '@/composables/useAppMode';

interface Props {
    series: Series;
    active?: boolean
}

const { isAppOnline } = useAppMode()

const emit = defineEmits(['favorited', 'unfavorited'])
const props = withDefaults(defineProps<Props>(), {
});
function encodePath(path: string) {
    return path.split('/').map(encodeURIComponent).join('/')
}
</script>

<template>
    <div
        :class="[
      'rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm bg-white dark:bg-gray-900 transition hover:shadow-md',
      props.active ? 'ring-2 ring-blue-500' : ''
    ]"
    >
        <!-- Thumbnail as image inside a link -->
        <Link
            :href="route('content-index', { id: props.series.id })"
            class="block aspect-video w-full"
        >
            <img :src="`${encodePath(props.series.thumbnail)}`" alt="Thumbnail" />
        </Link>

        <!-- Info + Actions -->
        <div class="p-3 flex items-start justify-between gap-2">
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                    {{ props.series.name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ props.series.author?.name }}
                </p>
            </div>

            <div class="flex items-center cursor-pointer gap-2 shrink-0">
                <FavoritesButton
                    @unfavorited="(event) => emit('unfavorited', event)"
                    :content="props.series"
                    content-entity="series"
                />

                <div>
                    <ShowInFolderButton
                        v-if="props.series.downloadable_content?.length < 1"
                        :content="props.series"
                        content-entity="series"
                    />
                    <DownloadButton
                        v-else-if="isAppOnline()"
                        :id="props.series.id"
                        class="hover:text-green-500"
                        content-entity="series"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
