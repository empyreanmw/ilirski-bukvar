<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { AppSettings, type BreadcrumbItem, Category, type SharedData } from '@/types';
import Icon from '@/components/Icon.vue';
import axios from 'axios';
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { computed, onMounted, ref } from 'vue';
import Dialog from '@/components/content/Dialog.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()

interface Props {
    videoQualities: number[];
    categories: Category[];
}
defineProps<Props>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('settings.downloads.title'),
        href: '/settings/downloads',
    },
];

const page = usePage<SharedData>();
const appSettings = page.props.appSettings as AppSettings;
const categories = page.props.categories as Category[];
const $toast = useToast();
const selectedCategories = ref<number[]>([]);
const totalSelectedSizeGb = computed(() => {
    const selected = categories.filter(category => selectedCategories.value.includes(category.id));
    const totalMb = selected.reduce((sum, category) => sum + category.size, 0);
    return (totalMb / 1024).toFixed(2); // Convert MB to GB
});
onMounted(() => {
    selectedCategories.value = categories.map(category => category.id);
});
const form = useForm({
    video_quality: appSettings.video_quality,
    download_path: appSettings.download_path
});

const submit = () => {
    form.put(route('settings.update-downloads'), {
        preserveScroll: true,
    });
};

const selectFolder = (event: MouseEvent):void => {
    event.preventDefault();
    axios.get('/dialog', {
    }).then (response => {
        form.download_path = response.data.folderPath
    });
}
const downloadAll = () => {
    axios.post('/download-all')
        .then(response => {
            $toast.open({
                message: t('download_manager.download_started'),
                type: 'success',
                duration: 5000
            });
        }).catch(error => {
        $toast.open({
            message: error.response.data.error,
            type: 'error',
            duration: 5000
        });
    })
}
const deleteAll = () => {
    axios.post('/delete-all')
        .then(response => {
            $toast.open({
                message: t('download_manager.files_deleted'),
                type: 'success',
                duration: 5000
            });
        }).catch(error => {
        $toast.open({
            message: error.response.data.error,
            type: 'error',
            duration: 5000
        });
    })

}

const showDownloadConfirm = ref(false);
const showDeleteConfirm = ref(false);

const confirmDownload = () => {
    showDownloadConfirm.value = false;
    axios.post('/download-all', {
        categories: selectedCategories.value
    })
        .then(response => {
            $toast.open({
                message: t('download_manager.download_started'),
                type: 'success',
                duration: 5000
            });
        }).catch(error => {
        $toast.open({
            message: error.response.data.error,
            type: 'error',
            duration: 5000
        });
    });
};


const confirmDelete = () => {
    showDeleteConfirm.value = false;
    deleteAll();
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title=" t('settings.downloads.title')" />
        <SettingsLayout>
            <div class="flex flex-col space-y-10 max-w-4xl">
                <!-- Download Info Section -->
                <section>
                    <HeadingSmall :title=" t('settings.downloads.subtitle')" :description=" t('settings.downloads.description')" />
                    <form @submit.prevent="submit" class="grid gap-6 mt-6">
                        <!-- Video Quality -->
                        <div>
                            <Label for="video_quality">{{t('settings.downloads.video_quality')}}</Label>
                            <select
                                id="video_quality"
                                class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 text-sm shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 px-3 py-2"
                                v-model="form.video_quality"
                                required
                            >
                                <option v-for="videoQuality in videoQualities" :key="videoQuality">{{ videoQuality }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.video_quality" />
                        </div>
                        <Dialog
                            :title=" t('settings.downloads.download_path')"
                            type="folder"
                            :model="form.download_path"
                            @file-selected="(event) => form.download_path = event">
                            <InputError class="mt-2" :message="form.errors.download_path" />
                        </Dialog>

                        <!-- Save Button -->
                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing" class="px-6 py-2">{{t('general.form.save')}}</Button>
                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">{{t('general.form.saved')}}</p>
                            </Transition>
                        </div>
                    </form>
                </section>

                <!-- Divider -->
                <div class="border-t pt-6" />

                <section>
                    <HeadingSmall :title=" t('settings.downloads.manage_downloads')" :description=" t('settings.downloads.manage_downloads_description')" />

                    <div class="mt-6 grid grid-cols-2 gap-6">
                        <!-- Download all -->
                        <div class="flex items-start gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex-shrink-0 mt-1">
                                <Icon name="download" class="text-primary-600 w-5 h-5" />
                            </div>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800 dark:text-white">{{t('settings.downloads.download_all_title')}}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{t('settings.downloads.download_all_description')}}</span>
                                <Button @click="showDownloadConfirm = true" class="cursor-pointer mt-2 w-max px-4 py-1.5">{{t('settings.downloads.download_all_title')}}</Button>
                            </div>
                        </div>

                        <!-- Delete all -->
                        <div class="flex items-start gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex-shrink-0 mt-1">
                                <Icon name="trash" class="text-red-600 w-5 h-5" />
                            </div>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800 dark:text-white">{{t('settings.downloads.delete_all_title')}}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{t('settings.downloads.delete_all_description')}}</span>
                                <!-- For Delete -->
                                <Button @click="showDeleteConfirm = true" class="cursor-pointer mt-2 w-max px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white">{{t('settings.downloads.delete_all_title')}}</Button>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Download Confirm Modal -->
                <Teleport to="body">
                    <div v-if="showDownloadConfirm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full shadow-lg space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{t('settings.password.download_modal.title')}}</h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{t('settings.password.download_modal.description')}}</p>

                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                <div
                                    v-for="category in categories"
                                    :key="category.id"
                                    class="flex items-center justify-between gap-2 pl-4 pr-2"
                                >
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            :value="category.id"
                                            v-model="selectedCategories"
                                            class="cursor-pointer rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                        />
                                        <label class="text-sm text-gray-700 dark:text-gray-300 capitalize">
                                            {{ t('general.' + category.name) }}
                                        </label>
                                    </div>
                                    <!-- Size Badge -->
                                    <div class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded px-2 py-0.5">
                                        {{ (category.size / 1024).toFixed(2) }} GB
                                    </div>
                                </div>
                            </div>

                            <!-- Total Size -->
                            <div class="mt-4 flex justify-end items-center text-sm text-gray-700 dark:text-gray-300">
                                {{t('settings.password.download_modal.total')}}
                                <span class="ml-2 font-semibold text-gray-900 dark:text-white">
        {{ totalSelectedSizeGb }} GB
    </span>
                            </div>

                            <div class="flex justify-end gap-2 mt-6">
                                <Button class="cursor-pointer" variant="ghost" @click="showDownloadConfirm = false">{{t('general.form.cancel')}}</Button>
                                <Button
                                    :disabled="selectedCategories.length === 0"
                                    @click="confirmDownload"
                                    class="cursor-pointer"
                                >
                                    {{t('settings.password.download_modal.download_selected')}}
                                </Button>
                            </div>
                        </div>
                    </div>
                </Teleport>


                <!-- Delete Confirm Modal -->
                <Teleport to="body">
                    <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full shadow-lg space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{t('settings.password.delete_modal.title')}}</h2>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{t('settings.password.delete_modal.description')}}</p>
                            <div class="flex justify-end gap-2 mt-4">
                                <Button class="cursor-pointer" variant="ghost" @click="showDeleteConfirm = false">{{t('general.form.cancel')}}</Button>
                                <Button class="cursor-pointer bg-red-600 text-white hover:bg-red-700" @click="confirmDelete">{{t('settings.password.delete_modal.submit')}}</Button>
                            </div>
                        </div>
                    </div>
                </Teleport>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
