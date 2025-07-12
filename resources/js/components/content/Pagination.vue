<script setup lang="ts">
import 'vue-toast-notification/dist/theme-sugar.css'
import { Link } from '@/types'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { computed } from 'vue'

const { t } = useI18n()

interface Props {
    link: Link[]
    isBook?: boolean
}
const props = withDefaults(defineProps<Props>(), {
    isBook: false
})

const goTo = (url: string | null): void => {
    if (url) {
        const href = props.isBook ? `${url}&tab=book` : url
        router.visit(href, {
            preserveState: false,
            preserveScroll: true
        })
    }
}

const translateLabel = (label: string): string => {
    if (label === '&laquo; Previous') return t('components.pagination.previous')
    if (label === 'Next &raquo;') return t('components.pagination.next')
    return label
}

const visibleLinks = computed(() => {
    const all = props.link
    if (all.length <= 1) return []

    const numberedLinks = all.filter(l => !l.label.includes('Previous') && !l.label.includes('Next'))
    const activeIndex = numberedLinks.findIndex(l => l.active)

    const display: Link[] = []

    // Always include shortcut buttons (not numbered, not active)
    const firstShortcut = { ...numberedLinks[0], label: t('components.pagination.first'), active: false }
    const lastShortcut = { ...numberedLinks[numberedLinks.length - 1], label: t('components.pagination.last'), active: false }
    display.push(firstShortcut)

    for (let i = 0; i < numberedLinks.length; i++) {
        const item = numberedLinks[i]
        const isCurrent = i === activeIndex
        const isNear = Math.abs(i - activeIndex) <= 1

        const isFirstNumbered = i === 0
        const isLastNumbered = i === numberedLinks.length - 1
        const isFarFromCurrent = Math.abs(i - activeIndex) > 2

        // Only show actual 1/9 if current page is near edges
        const showEdgeNumber =
            (isFirstNumbered && activeIndex <= 2) ||
            (isLastNumbered && activeIndex >= numberedLinks.length - 3)

        if (isCurrent || isNear || showEdgeNumber) {
            display.push(item)
        } else if (i === activeIndex - 2 || i === activeIndex + 2) {
            display.push({ label: '...', url: null, active: false } as Link)
        }
    }

    display.push(lastShortcut)

    // Remove duplicate ellipses
    return display.filter((item, idx, self) =>
        idx === 0 || item.label !== '...' || self[idx - 1].label !== '...')
})


</script>

<template>
    <div v-if="visibleLinks.length > 3" class="overflow-x-auto select-none">
        <div class="inline-flex gap-2 px-1">
            <button
                v-for="(link, index) in visibleLinks"
                :key="index"
                @click="goTo(link.url)"
                :disabled="!link.url"
                v-html="translateLabel(link.label)"
                class="px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200 whitespace-nowrap"
                :class="{
          'bg-blue-600 text-white shadow-md ring-2 ring-blue-300': link.active,
          'bg-white text-gray-700 hover:bg-gray-100 hover:text-black dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700': !link.active,
          'opacity-50 cursor-not-allowed': !link.url
        }"
            />
        </div>
    </div>
</template>


