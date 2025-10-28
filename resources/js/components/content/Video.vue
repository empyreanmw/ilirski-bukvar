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
import axios from 'axios'
import { reactive, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import CompleteContent from './CompleteContent.vue';

interface Props {
    video: Content;
    active?: boolean
}

const { t } = useI18n()
const emit = defineEmits(['favorited', 'unfavorited', 'last-watched', 'completed'])
const props = withDefaults(defineProps<Props>(), {
    active: false
});
const videoState = reactive({ ...props.video })
const { isAppOnline } = useAppMode()
const setLastWatched = async () => {
  videoState.last_watched = true;
  emit('last-watched', props.video.id); // Emit the ID of the newly watched video

  await axios.put('/set-last-watched', {
    content_id: props.video.id,
    last_watched: true
  });

  handleCompletionUpdate(true)
};
const handleCompletionUpdate = (value: boolean) => {
  emit('completed', props.video.id, value); // Emit the ID of the newly watched video
  videoState.completed = value
}
watch(
  () => props.video,
  (newVideo) => {
    videoState.last_watched = newVideo.last_watched;
    videoState.completed = newVideo.completed;
    // Add other fields if needed
  },
  { deep: true }
);
</script>

<template>
    <div
        :class="['rounded-xl border  border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-900',
            props.active ? 'ring-2 ring-yellow-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900' : ''
        ]"
    >
        <!-- Video Player -->
        <div @click="setLastWatched" class="aspect-video w-full">
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
        <div class="flex flex-wrap items-center justify-between gap-3 p-4">
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
            <div class="flex-none">
                <div class="flex flex-nowrap items-center gap-2 whitespace-nowrap">
                    <!-- All three buttons get identical boxes -->
                    <FavoritesButton
                        class="inline-flex h-9 w-9 items-center justify-center shrink-0"
                        @unfavorited="(event) => emit('unfavorited', event)"
                        :content="props.video"
                        content-entity="content"
                    />

                    <ShowInFolderButton
                        v-if="props.video.downloaded"
                        :content="props.video"
                        content-entity="content"
                        class="inline-flex h-9 w-9 items-center justify-center shrink-0"
                    />

                    <DownloadButton
                        v-else-if="isAppOnline()"
                        :id="props.video.id"
                        content-entity="content"
                        class="inline-flex h-9 w-9 items-center justify-center shrink-0"
                    />

                    <CompleteContent
                        @update:completed="handleCompletionUpdate"
                        :completed="videoState.completed"
                        :id="props.video.id"
                        class="inline-flex h-9 w-9 items-center justify-center shrink-0"
                    />
                </div>
            </div>
        </div>
        <div class="pl-4 pb-2 h-6 flex items-center space-x-2">
            <span
                v-if="videoState.completed"
                class="inline-block rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200"
            >
                {{ t('general.watched') }}
            </span>
            <span
                v-if="videoState.last_watched"
                class="inline-block rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200"
            >
                {{ t('general.last_watch') }}
            </span>
        </div>
    </div>
</template>

<style scoped>
/* Ensure Video.js fills the box */
:deep(.video-js) {
  width: 100% !important;
  height: 100% !important;
}

/* Belt-and-suspenders centering for the big play button */
:deep(.vjs-big-play-button) {
  left: 50% !important;
  top: 50% !important;
  transform: translate(-50%, -50%) !important;
}
</style>