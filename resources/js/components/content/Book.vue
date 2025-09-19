<script setup lang="ts">
import DownloadButton from '@/components/content/DownloadButton.vue';
import { Content } from '@/types';
import { useForm } from '@inertiajs/vue3';
import FavoritesButton from '@/components/content/FavoritesButton.vue';
import ShowInFolderButton from '@/components/content/ShowInFolderButton.vue';
import { useAppMode } from '@/composables/useAppMode';
import { reactive } from 'vue'
import CompleteContent from './CompleteContent.vue';
import { useI18n } from 'vue-i18n'

interface Props {
    book: Content;
    active?: boolean
}

const { t } = useI18n()
const props = withDefaults(defineProps<Props>(), {
    active: false
});
const emit = defineEmits(['favorited', 'unfavorited'])
const { isAppOnline } = useAppMode()
const bookState = reactive({ ...props.book })
const handleCompletionUpdate = (value: boolean) => {
  bookState.completed = value
}
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
    <div
        :id="`book-${props.book.id}`"
        :class="[
        'relative flex flex-col h-full rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 transition shadow-sm hover:shadow-md p-2',
        props.active ? 'ring-2 ring-yellow-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-900' : ''
    ]"
    >
        <!-- Image -->
        <div @click="openFile(props.book.id)" class="flex h-64 w-full cursor-pointer items-center justify-center bg-white pt-4 dark:bg-gray-900">
            <img :src="props.book.thumbnail_url" alt="Cover" class="h-full w-auto object-contain" />
        </div>

        <!-- Info + Actions wrapper -->
        <div class="flex flex-col flex-1 justify-between p-4">
            <!-- Title + Author -->
            <div class="flex-1 min-h-0">
                <h3
                    class="text-base leading-tight font-semibold text-gray-800 dark:text-white break-words overflow-hidden"
                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"
                    :title="props.book.title"
                >
                    {{ props.book.title }}
                </h3>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    {{ props.book.author?.name }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center gap-2 pt-4">
                <FavoritesButton
                    @unfavorited="event => emit('unfavorited', event)"
                    :content="props.book"
                    content-entity="content"
                />

                <template v-if="props.book.downloaded">
                    <ShowInFolderButton
                        :content="props.book"
                        content-entity="content"
                        class="align-middle"
                    />
                </template>

                <template v-else-if="isAppOnline()">
                    <DownloadButton
                        :id="props.book.id"
                        content-entity="content"
                        class="hover:text-green-500 align-middle"
                    />
                </template>
    <CompleteContent
      @update:completed="handleCompletionUpdate"
      :completed="bookState.completed"
      :id="props.book.id"
    />
            </div>
        </div>
        <div class="pl-4 pb-2 h-6 flex items-center space-x-2">
  <span
    v-if="bookState.completed"
    class="inline-block rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200"
  >
    {{ t('general.read') }}
  </span>
</div>
    </div>
</template>
