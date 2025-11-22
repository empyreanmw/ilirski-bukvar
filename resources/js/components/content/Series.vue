<script setup lang="ts">
import { Series } from '@/types'
import DownloadButton from '@/components/content/DownloadButton.vue'
import { Link } from '@inertiajs/vue3'
import FavoritesButton from '@/components/content/FavoritesButton.vue'
import ShowInFolderButton from '@/components/content/ShowInFolderButton.vue'
import { useAppMode } from '@/composables/useAppMode'

interface Props {
    series: Series
    active?: boolean
    limitHeight?: boolean
    withFavorites?: boolean
}

const { isAppOnline } = useAppMode()
const emit = defineEmits(['favorited', 'unfavorited'])
const props = withDefaults(defineProps<Props>(), {
    limitHeight: true,
    withFavorites: true
})
const encodePath = (path: string) => path.split('/').map(encodeURIComponent).join('/')
</script>

<template>
    <div
        :class="[
      'rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm bg-white dark:bg-gray-900 transition hover:shadow-md',
      props.active ? 'ring-2 ring-blue-500' : ''
    ]"
    >
        <!-- Thumbnail -->
        <Link :href="route('content-index', { id: props.series.id })" class="block aspect-video w-full">
            <img
                :src="`${encodePath(props.series.thumbnail)}`"
                @error="event => event.target.src = '/thumbnails/backup_image_video.jpg'"
                alt="Ilirski Bukvar thumbnail"
            />
        </Link>

        <!-- Info + Actions (responsive layout) -->
        <div class="p-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <!-- Series Info -->
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                    {{ props.series.name }}
                </p>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ props.series.author?.name }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2 justify-end sm:flex-nowrap">
                <FavoritesButton
                    v-if="withFavorites"
                    @unfavorited="(event) => emit('unfavorited', event)"
                    :content="props.series"
                    content-entity="series"
                />

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
</template>
