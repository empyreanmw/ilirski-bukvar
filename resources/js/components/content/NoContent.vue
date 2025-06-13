<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
interface Props {
    icon: string;
    type: string;
}

const props = defineProps<Props>();

</script>

<template>
    <div class="flex flex-col items-center justify-center h-[60vh] text-center text-gray-500 dark:text-gray-400">
        <Icon class="h-10 w-10 mb-2" :name="props.icon"/>
        <h2 class="text-lg font-semibold">{{ t('general.no_' + props.type) }}</h2>
        <p class="text-sm">{{ t('general.no_'+props.type+'_description') }}</p>
    </div>
</template>
