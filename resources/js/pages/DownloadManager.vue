<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n'
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { type BreadcrumbItem, downloadOutput, JobRequest, Link } from '@/types';
import LvProgressBar from 'lightvue/progress-bar';
import { emitter } from '@/utils/eventBus';
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import { Button } from '@/components/ui/button';
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import Pagination from '@/components/content/Pagination.vue';

interface Props {
    jobRequests: {
        data: JobRequest[]
        links: Link[]
    }
}
const { t } = useI18n()
const props = defineProps<Props>()
const items = ref(props.jobRequests.data)
const links = props.jobRequests.links
const $toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t(`general.download_manager`),
        href: `/download-manager`,
    },
];
const getFileSize = (request: JobRequest): string => {
    if (request.size) {
        return request.size + 'Mb'
    }
    if (request.job_reference.size) {
        return request.job_reference.size + 'Mb'
    }

    return 'Unknown'
}
const handleDownloadOutput = (payload: downloadOutput) => {
    items.value = items.value.map(item =>
        item.job_reference_id === payload.downloadOutput.content.id ? { ...item, ...{
                request_reference: payload.downloadOutput.content,
                progress: payload.downloadOutput.percent,
                job_status: payload.downloadOutput.percent == 100 ? 'done' : item.job_status,
                size: payload.downloadOutput.total
            } } : item
    )
}
const handleStatusUpdate = (payload: {jobRequest: JobRequest}) => {
    items.value = items.value.map(item =>
        item.job_reference_id === payload.jobRequest.reference.id ? { ...item, ...{
                request_reference: item.reference,
                progress: item.progress,
                job_status: payload.jobRequest.job_status,
                size: item.size
            } } : item
    )
}
const cancelDownload = (request: JobRequest) => {
    axios.get('/cancel-download/' + request.id).then(() => {
        items.value = items.value.map(item =>
        item.id === request.id ? {...item, ...{
                request_reference: request.job_reference,
                progress: 0,
                job_status: 'cancelled',
                size: item.size ?? 0
            } } : item)
    })
}
const cancelAllDownloads = (event: MouseEvent) => {
    event.stopPropagation()
    axios.post('/cancel-all-downloads')
        .then(() => {
            items.value = items.value.map(item => {
                if (!['failed', 'done', 'in_progress'].includes(item.job_status)) {
                    return {
                        ...item,
                        job_status: 'cancelled'
                    };
                }
                return item;
            });
        })
}
const download  = (request: JobRequest) => {
    axios.post('/content/download', {
        id: request.job_reference.id
    }).then (() => {
    }).catch(() => {
        $toast.open({
            message: 'Could not start file download',
            type: 'error',
            duration: 5000
        });
    })
}

onMounted(() => {
    emitter.on('download-output', handleDownloadOutput)
    emitter.on('request-status-updated', handleStatusUpdate)
})
onBeforeUnmount(() => {
    emitter.off('download-output')
    emitter.off('request-status-update')
})

</script>

<template>
    <Head :title="t(`general.download_manager`)" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Cancel All Button -->
            <div v-if="items.length" class="flex justify-end mb-2">
                <Button @click="cancelAllDownloads" type="button" class="cursor-pointer px-4 py-2 text-sm bg-red-600 text-white hover:bg-red-700">
                    Cancel all
                </Button>
            </div>
            <!-- Download Table -->
            <div v-if="items.length" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3">File Name</th>
                        <th class="px-6 py-3">Progress</th>
                        <th class="px-6 py-3">Size</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(request, index) in items"
                        :key="index"
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    >
                        <!-- Title -->
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ request.job_reference.title }}
                        </th>

                        <!-- Progress -->
                        <td class="px-6 py-4 text-white">
                            <LvProgressBar class="w-40" :showValue="true" color="#0288d1" :value="request.progress" />
                        </td>

                        <!-- Size -->
                        <td class="px-6 py-4">
                            {{ getFileSize(request) }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-2">
                                <Icon v-if="request.job_status === 'in_progress'" name="loader" class="animate-spin w-4 h-4 text-yellow-500" />
                                <Icon v-else-if="request.job_status === 'queued'" name="clock" class="w-4 h-4 text-blue-400" />
                                <Icon v-else-if="request.job_status === 'done'" name="check-circle" class="w-4 h-4 text-green-600" />
                                <Icon v-else-if="request.job_status === 'cancelled'" name="ban" class="w-4 h-4 text-gray-500" />
                                <Icon v-else-if="request.job_status === 'failed'" name="alert-circle" class="w-4 h-4 text-red-600" />
                                <span>{{ t('general.download_status.' + request.job_status) }}</span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 space-x-2">
                            <Icon
                                title="Cancel"
                                v-if="!['cancelled', 'in_progress'].includes(request.job_status)"
                                @click="cancelDownload(request)"
                                name="x-icon"
                                class="cursor-pointer text-red-500 hover:text-red-700"
                            />
                            <Icon
                                title="Restart"
                                v-if="['cancelled', 'failed'].includes(request.job_status)"
                                @click="download(request)"
                                name="download"
                                class="cursor-pointer text-blue-500 hover:text-blue-700"
                            />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <Pagination class="p-2" :link="links"/>
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center h-full py-20 text-center text-gray-500 dark:text-gray-400">
                <Icon name="download" class="w-12 h-12 mb-4 text-gray-400" />
                <h2 class="text-lg font-semibold text-gray-700 dark:text-white">No active downloads</h2>
                <p class="text-sm mt-1">You have no active downloads at the moment.</p>
            </div>
        </div>
    </AppLayout>
</template>
