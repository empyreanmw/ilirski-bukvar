<script setup lang="ts">
import { ref, defineExpose, watch, onBeforeUnmount } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import Icon from '@/components/Icon.vue'
import { useToast } from 'vue-toast-notification'

const { t } = useI18n()
const $toast = useToast()

const visible = ref(false)
const promotionTitle = ref('')
const promotionDescription = ref('')

interface PromotionPayload {
  uuid: string
  title: string
  description: string
}

// main form used for BOTH enroll + reject
const form = useForm({
  uuid: '',
  enrolled: false,
  rejected: false,
  email: '',
  first_name: '',
  last_name: '',
  street: '',
  city: '',
  phone_number: '',
})

function open(promotion: PromotionPayload | PromotionPayload[] | any) {
  const item = Array.isArray(promotion) ? promotion[0] : promotion

  if (!item) {
    console.warn('PromotionModal.open called without promotion')
    return
  }

  promotionTitle.value = item.title ?? ''
  promotionDescription.value = item.description ?? ''
  form.uuid = item.uuid ?? ''

  visible.value = true
}

function closeModalOnly() {
  visible.value = false
}

// called when user clicks "Cancel" (reject promotion)
function cancelAndTrack() {
  if (!form.uuid) {
    visible.value = false
    return
  }

  form.enrolled = false
  form.rejected = true
  form.email = ''
  form.first_name = ''
  form.last_name = ''
  form.street = ''
  form.city = ''
  form.phone_number = ''

  form.clearErrors()

  form.post('/promotions', {
    preserveScroll: true,
    onFinish: () => {
      visible.value = false
    },
  })
}

// helper: all fields must be filled for submit
function validateForm() {
  form.clearErrors()

  let valid = true

  if (!form.email) {
    form.setError(
      'email',
      t('components.promotion_modal.email_required') || 'Email is required',
    )
    valid = false
  }
  if (!form.first_name) {
    form.setError(
      'first_name',
      t('components.promotion_modal.first_name_required') || 'First name is required',
    )
    valid = false
  }
  if (!form.last_name) {
    form.setError(
      'last_name',
      t('components.promotion_modal.last_name_required') || 'Last name is required',
    )
    valid = false
  }
  if (!form.street) {
    form.setError(
      'street',
      t('components.promotion_modal.street_required') || 'Street is required',
    )
    valid = false
  }
  if (!form.city) {
    form.setError(
      'city',
      t('components.promotion_modal.city_required') || 'City is required',
    )
    valid = false
  }
  if (!form.phone_number) {
    form.setError(
      'phone_number',
      t('components.promotion_modal.phone_required') || 'Phone number is required',
    )
    valid = false
  }

  if (!valid) {
    $toast.open({
      message:
        t('components.promotion_modal.all_required') ||
        'Please fill in all fields before submitting.',
      type: 'warning',
      duration: 5000,
    })
  }

  return valid
}

// called when user submits form (enroll)
function submit() {
  // require ALL fields
  if (!validateForm()) {
    return
  }

  form.enrolled = true
  form.rejected = false

  form.post('/promotions', {
    preserveScroll: true,
    onSuccess: () => {
      $toast.open({
        message:
          t('components.promotion_modal.success') ||
          'Form submitted successfully',
        type: 'success',
        duration: 5000,
      })
      form.reset()
      visible.value = false
    },
    onError: () => {
      $toast.open({
        message:
          t('components.promotion_modal.error') ||
          'Something went wrong while submitting the form',
        type: 'error',
        duration: 5000,
      })
    },
  })
}

// 🔒 lock page scroll when modal is open
const originalBodyOverflow = document.body.style.overflow
const originalHtmlOverflow = document.documentElement.style.overflow

watch(visible, (val) => {
  if (val) {
    document.body.style.overflow = 'hidden'
    document.documentElement.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = originalBodyOverflow
    document.documentElement.style.overflow = originalHtmlOverflow
  }
})

onBeforeUnmount(() => {
  document.body.style.overflow = originalBodyOverflow
  document.documentElement.style.overflow = originalHtmlOverflow
})

defineExpose({ open, close: closeModalOnly })
</script>

<template>
  <transition name="fade">
    <div
      v-if="visible"
      class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 dark:bg-gray-900/80 backdrop-blur-md overflow"
    >
      <!-- Modal panel with hard height limit -->
      <transition name="scale-fade">
        <div
          class="relative w-full max-w-xl mx-4 rounded-3xl shadow-xl border
                 bg-white/90 text-gray-800 border-gray-200
                 dark:bg-gray-800/90 dark:text-gray-100 dark:border-gray-700
                p-4 sm:p-6 space-y-6 overflow"
        >
          <!-- Header -->
          <div class="text-center space-y-3">
            <Icon name="star" class="mx-auto h-10 w-10 text-blue-600 dark:text-blue-400" />
            <h2 class="text-2xl font-semibold tracking-wide">
              {{ promotionTitle }}
            </h2>
            <span
              v-html="promotionDescription"
              class="block text-sm text-gray-500 dark:text-gray-400"
            />
          </div>

          <!-- Form -->
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid gap-3 md:grid-cols-2">
              <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                  {{ t('components.promotion_modal.email') }}
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="mt-1 block w-full rounded-md border px-3 py-2 text-sm
                         bg-white text-gray-900 border-gray-300
                         focus:border-blue-500 focus:ring-blue-500
                         dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
                />
                <p v-if="form.errors.email" class="text-xs text-red-400 mt-1">
                  {{ form.errors.email }}
                </p>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                  {{ t('components.promotion_modal.first_name') }}
                </label>
                <input
                  v-model="form.first_name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border px-3 py-2 text-sm
                         bg-white text-gray-900 border-gray-300
                         focus:border-blue-500 focus:ring-blue-500
                         dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
                />
                <p v-if="form.errors.first_name" class="text-xs text-red-400 mt-1">
                  {{ form.errors.first_name }}
                </p>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                  {{ t('components.promotion_modal.last_name') }}
                </label>
                <input
                  v-model="form.last_name"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border px-3 py-2 text-sm
                         bg-white text-gray-900 border-gray-300
                         focus:border-blue-500 focus:ring-blue-500
                         dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
                />
                <p v-if="form.errors.last_name" class="text-xs text-red-400 mt-1">
                  {{ form.errors.last_name }}
                </p>
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                  {{ t('components.promotion_modal.street') }}
                </label>
                <input
                  v-model="form.street"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border px-3 py-2 text-sm
                         bg-white text-gray-900 border-gray-300
                         focus:border-blue-500 focus:ring-blue-500
                         dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
                />
                <p v-if="form.errors.street" class="text-xs text-red-400 mt-1">
                  {{ form.errors.street }}
                </p>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                  {{ t('components.promotion_modal.city') }}
                </label>
                <input
                  v-model="form.city"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border px-3 py-2 text-sm
                         bg-white text-gray-900 border-gray-300
                         focus:border-blue-500 focus:ring-blue-500
                         dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
                />
                <p v-if="form.errors.city" class="text-xs text-red-400 mt-1">
                  {{ form.errors.city }}
                </p>
              </div>

              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                  {{ t('components.promotion_modal.phone') }}
                </label>
                <input
                  v-model="form.phone_number"
                  type="text"
                  required
                  class="mt-1 block w-full rounded-md border px-3 py-2 text-sm
                         bg-white text-gray-900 border-gray-300
                         focus:border-blue-500 focus:ring-blue-500
                         dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700"
                />
                <p v-if="form.errors.phone_number" class="text-xs text-red-400 mt-1">
                  {{ form.errors.phone_number }}
                </p>
              </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
              <button
                type="button"
                class="px-3 py-2 rounded-lg border text-xs sm:text-sm
                       border-gray-300 text-gray-700 hover:bg-gray-100
                       dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                @click="cancelAndTrack"
              >
                {{ t('components.promotion_modal.cancel') }}
              </button>

              <button
                type="submit"
                class="px-3 py-2 rounded-lg text-xs sm:text-sm font-medium
                       bg-blue-600 text-white hover:bg-blue-500
                       disabled:opacity-60 disabled:cursor-not-allowed"
                :disabled="form.processing"
              >
                <span v-if="form.processing">
                  {{ t('components.promotion_modal.sending') }}
                </span>
                <span v-else>
                  {{ t('components.promotion_modal.submit') }}
                </span>
              </button>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.overflow {
  overflow: auto;
}
.scale-fade-enter-active {
  transition: transform 0.4s ease, opacity 0.3s ease;
}
.scale-fade-enter-from {
  transform: scale(0.92);
  opacity: 0;
}
.scale-fade-leave-active {
  transition: transform 0.2s ease, opacity 0.2s ease;
}
.scale-fade-leave-to {
  transform: scale(0.95);
  opacity: 0;
}
</style>
