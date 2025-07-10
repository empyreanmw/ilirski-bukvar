<script setup lang="ts">
import Icon from '@/components/Icon.vue'
import 'vue-toast-notification/dist/theme-sugar.css'
import { Content, downloadOutput, JobRequest } from '@/types'
import LvProgressBar from 'lightvue/progress-bar'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { emitter } from '@/utils/eventBus'
import { useToast } from 'vue-toast-notification'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const $toast = useToast()
const downloadInProgress = ref(false)
const downloads = ref<Content[]>([])

const addOrUpdateDownload = (newItem: {
    percent: number
    total: number
    content: Content
}) => {
    const index = downloads.value.findIndex(item => item.content.id === newItem.content.id)

    if (index !== -1) {
        downloads.value[index] = { ...downloads.value[index], ...newItem }
    } else {
        downloads.value.push(newItem)
    }
}

const allDownloadsComplete = computed(() =>
    downloads.value.length > 0 && downloads.value.every(item => item.percent === 100)
)

const handleStatusUpdate = (payload: { jobRequest: JobRequest }) => {
    if (
        payload.jobRequest.job_status === 'failed' ||
        payload.jobRequest.job_status === 'cancelled' ||
        payload.jobRequest.job_status === 'done'
    ) {
        downloads.value = downloads.value.filter(item => item.content.id !== payload.jobRequest.reference.id)
    }
    if (downloads.value.length === 0) {
        downloadInProgress.value = false
    }
}

const getActiveDownloads = () => {
    axios.get('/content/downloads/active').then(response => {
        if (response.data.downloads.length > 0) {
            downloadInProgress.value = true
        }
        downloads.value = response.data.downloads
    })
}

onMounted(() => {
    getActiveDownloads()
    emitter.on('download-output', handleDownloadOutput)
    emitter.on('file-added-to-queue', handleFileAddedToQueue)
    emitter.on('request-status-updated', handleStatusUpdate)
})

onBeforeUnmount(() => {
    emitter.off('download-output')
    emitter.off('file-added-to-queue')
    emitter.off('request-status-updated')
})

const handleFileAddedToQueue = (payload: downloadOutput) => {
    downloadInProgress.value = true
    addOrUpdateDownload(payload.downloadOutput)
}

const handleDownloadOutput = (payload: downloadOutput) => {
    addOrUpdateDownload(payload.downloadOutput)
    downloadInProgress.value = true

    if (payload.downloadOutput.percent === 100) {
        $toast.open({
            message: getDownloadMessage(payload.downloadOutput.content.title.toLowerCase()),
            type: 'success',
            duration: 5000
        })

        if (allDownloadsComplete.value) {
            downloadInProgress.value = false
        }
    }
}

const getDownloadMessage = (title: string) =>
    t('components.content_download_bar.download_success_message', { title })
</script>

<template>
    <Menu as="div" class="fixed top-4 right-4 z-50 text-left">
        <div>
            <MenuButton
                v-if="downloadInProgress"
                class="cursor-pointer flex items-center gap-2 justify-center px-4 py-2 rounded-full
               bg-white dark:bg-gray-900 shadow-md ring-1 ring-gray-300 dark:ring-gray-700
               hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            >
                <LvProgressBar mode="indeterminate" class="w-6" :value="50" />
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ downloads.length }}</span>
            </MenuButton>
        </div>

        <transition
            v-if="downloadInProgress"
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-90"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-90"
        >
            <MenuItems
                class="absolute right-0 mt-3 w-80 origin-top-right rounded-xl
               bg-white dark:bg-gray-900 shadow-2xl ring-1 ring-black/10 dark:ring-white/10
               text-gray-700 dark:text-gray-200 focus:outline-none"
            >
                <div class="py-2 max-h-96 overflow-y-auto">
                    <MenuItem v-for="file in downloads.slice(0, 5)" :key="file.content.id" v-slot="{ active }">
                        <div :class="[active ? 'bg-gray-100 dark:bg-gray-800' : '']"
                             class="flex flex-col gap-2 px-4 py-3 text-sm transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full
                            bg-gray-100 dark:bg-gray-800">
                                    <Icon v-if="file.content.icon" :name="file.content.icon" class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                    <div v-else class="text-xs text-gray-500 dark:text-gray-400">📄</div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100 text-ellipsis whitespace-nowrap overflow-hidden">
                                        {{ file.content.title }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-blue-500 dark:bg-blue-400 transition-all duration-500"
                                        :style="{ width: `${file.percent}%` }"
                                    ></div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ file.percent }}%</span>
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                {{ file.downloaded }}MB / {{ file.total }}MB
                            </div>
                        </div>
                    </MenuItem>

                    <MenuItem>
                        <Link :href="route('download-manager')"
                              class="text-blue-500 dark:text-blue-400 hover:underline cursor-pointer
                         flex items-center gap-2 px-4 py-3 text-sm">
                            {{ t('components.content_download_bar.all_downloads') }}
                        </Link>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>
