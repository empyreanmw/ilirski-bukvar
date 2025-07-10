<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import {useToast} from 'vue-toast-notification';
import axios from 'axios';
import 'vue-toast-notification/dist/theme-sugar.css';
import { ContentEntity } from '@/types';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
const { t } = useI18n()

interface Props {
    id: number;
    contentEntity: ContentEntity;
}

const $toast = useToast();
const props = withDefaults(defineProps<Props>(), {
});
const downloadStarted = ref(false)

const download = () => {
    axios.post('/' + props.contentEntity + '/download', {
        id: props.id
    }).then (response => {
    }).catch(error => {
        $toast.open({
            message: t('components.download_button.download_error_message'),
            type: 'error',
            duration: 5000
        });
    })
    downloadStarted.value = true
}
</script>

<template>
    <button
        @click.prevent="download()"
        class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full bg-white/80 dark:bg-gray-800/80 shadow hover:text-yellow-500 transition"
        :disabled = "downloadStarted"
    >
        <Icon name="download" class="h-5 w-5 text-gray-700 hover:text-green-500 dark:text-gray-300" />
    </button>
</template>
