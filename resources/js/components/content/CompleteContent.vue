<script setup lang="ts">
import Icon from '@/components/Icon.vue'
import axios from 'axios'
import { useToast } from 'vue-toast-notification'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'

interface Props {
  id: number
  completed: boolean
}

const { t } = useI18n()
const $toast = useToast()
const props = defineProps<Props>()
const emit = defineEmits<{
  (e: 'update:completed', value: boolean): void
}>()

const isCompleted = ref(props.completed)

const complete = async (): Promise<void> => {
  try {
    await axios.put('/set-content-completion', {
      content_id: props.id,
      completed: !isCompleted.value,
    })

    isCompleted.value = !isCompleted.value
    emit('update:completed', isCompleted.value)
    if (isCompleted.value == true) {
        $toast.open({
        message: t('general.messages.content_completion_success'),
        type: 'success',
        duration: 5000,
        })
    }
  } catch (error) {
      console.error('Failed to update completion status', error)
  }
}
</script>
<template>
  <button
    @click="complete()"
    class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full bg-white/80 dark:bg-gray-800/80 shadow hover:text-yellow-500 transition"
  >
    <Icon
      name="eye"
      class="h-5 w-5 transition-colors duration-300"
      :class="isCompleted ? 'text-green-500' : 'text-gray-700 dark:text-gray-300'"
    />
  </button>
</template>
