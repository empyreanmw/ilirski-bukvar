<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { FileType } from '@/types';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import axios from 'axios';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n'

interface Props {
    type: FileType;
    title: string;
    model: string;
}

const { t } = useI18n()
const props = withDefaults(defineProps<Props>(), {
});
const modelValue = ref(props.model)

const selectFolder = (event: MouseEvent):void => {
    event.preventDefault();
    axios.post('/dialog', {
        file_type: props.type,
        default_path: props.model
    }).then (response => {
        modelValue.value = response.data.path;
        emit('fileSelected', modelValue.value)
    });
}
const emit = defineEmits(['fileSelected'])
</script>

<template>
    <div>
        <Label for="download_path">{{props.title}}</Label>
        <div class="flex gap-2 mt-1">
            <Input
                id="download_path"
                class="flex-1"
                disabled
                required
                v-model="modelValue"
            />
            <Button
                @click="selectFolder"
                type="button"
                class="cursor-pointer inline-flex items-center gap-1 whitespace-nowrap px-4 py-2"
            >
                <Icon name="folder" />
                <span>{{t('general.form.select')}}</span>
            </Button>
        </div>
        <slot/>
    </div>
</template>
