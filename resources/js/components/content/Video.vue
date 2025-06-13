<script setup lang="ts">
import { Content } from '@/types';
import DownloadButton from '@/components/content/DownloadButton.vue';
import LiteYouTubeEmbed from 'vue-lite-youtube-embed';
import 'vue-lite-youtube-embed/style.css';
import '@vidstack/player';
import FavoritesButton from '@/components/content/FavoritesButton.vue';
import ShowInFolderButton from '@/components/content/ShowInFolderButton.vue';
import { useAppMode } from '@/composables/useAppMode';
import OfflineVideoPlayer from '@/components/content/OfflineVideoPlayer.vue';
import { VideoPlayer } from '@videojs-player/vue'
import 'video.js/dist/video-js.css'
import TrackedIframe from '@/components/content/TrackedIframe.vue';

interface Props {
    video: Content;
    active?: boolean
}

const emit = defineEmits(['favorited', 'unfavorited'])
const props = withDefaults(defineProps<Props>(), {
    active: false
});
const { isAppOnline } = useAppMode()
</script>

<template>
    <div
        :class="['rounded-xl border  border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900',
            props.active ? 'ring-2 ring-yellow-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900' : ''
        ]"
    >
        <!-- Video Player -->
        <div class="aspect-video w-full">
            <LiteYouTubeEmbed
                v-if="isAppOnline() && video.player_type === 'youtube'"
                :id="props.video.url"
                :class="['h-full w-full bg-cover bg-center bg-no-repeat']"
                title="Video preview"
            />
            <video-player
                :fill = true
                v-else-if="isAppOnline() && video.player_type === 'regular_video'"
                :src="video.url"
                :poster="video.thumbnail_url"
                controls
                :loop="true"
                :volume="0.6"
            />
            <TrackedIframe
                v-else-if="isAppOnline() && video.player_type === 'google_drive'"
                :src="video.url"
            />
            <OfflineVideoPlayer v-else :video="props.video" />
        </div>

        <!-- Info + Actions -->
        <div class="flex items-start justify-between gap-2 p-4">
            <!-- Title + Author (truncated) -->
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-gray-800 dark:text-white">
                    {{ props.video.title }}
                </p>
                <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                    {{ props.video.author.name }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex shrink-0 items-center gap-2">
                <!-- Favorite -->
                <FavoritesButton  @unfavorited="(event) => emit('unfavorited', event)" :content="props.video" content-entity="content" />
                <!-- Download -->
                <div>
                    <ShowInFolderButton
                        v-if="props.video.downloaded"
                        :content="props.video"
                        content-entity="content"
                    />
                    <DownloadButton
                        v-else-if="isAppOnline()"
                        :id="props.video.id"
                        content-entity="content"
                        class="hover:text-green-500"
                        style="position: relative; top: -2px"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
