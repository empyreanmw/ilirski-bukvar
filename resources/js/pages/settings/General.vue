<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type AppSettings} from '@/types';
import Dialog from '@/components/content/Dialog.vue';
import { Button } from '@/components/ui/button';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('settings.general.title'),
        href: '/settings/general',
    },
];

const page = usePage<SharedData>();
const appSettings = page.props.appSettings as AppSettings;

const form = useForm({
    content_per_page: appSettings.content_per_page,
    video_player_path: appSettings.video_player_path,
    book_reader_path: appSettings.book_reader_path,
});

const submit = () => {
    form.put(route('settings.update-general'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="t('settings.general.title')" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall :title="t('settings.general.subtitle')" :description="t('settings.general.description')" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="content-per-page">{{t('settings.general.content_per_page')}}</Label>
                        <Input id="content-per-page" type="number" class="mt-1 block w-full" v-model="form.content_per_page" required />
                        <InputError class="mt-2" :message="form.errors.content_per_page" />
                    </div>
                    <!-- Download Path -->
                    <Dialog
                        @file-selected="(event) => form.video_player_path = event"
                        type="file"
                        :title="t('settings.general.dialog_video_title')"
                        :model="form.video_player_path">
                            <InputError class="mt-2" :message="form.errors.video_player_path" />
                    </Dialog>

                    <Dialog
                        @file-selected="(event) => form.book_reader_path = event"
                        type="file"
                        :title="t('settings.general.dialog_book_title')"
                        :model="form.book_reader_path">
                        <InputError class="mt-2" :message="form.errors.book_reader_path" />
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
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">{{t('general.form.saved')}}.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
