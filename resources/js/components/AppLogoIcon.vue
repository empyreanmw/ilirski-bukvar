<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();

</script>

<template>
    <div
        :class="['flex items-center gap-3 rounded-xl px-2 py-1', className]"
    >
        <!-- Light mode logo -->
        <img src="/images/logo.png" alt="Ilirski Bukvar" class="block dark:hidden w-12 h-12" />

        <!-- Dark mode logo -->
        <img src="/images/logo-dark.png" alt="Ilirski Bukvar" class="relative right-2 hidden dark:block w-16 h-16" />

        <div
            class="flex flex-col font-bold text-lg leading-[1.1] tracking-tight text-[#4f4739] dark:right-3 dark:relative dark:text-[#e8e1d9]"
            style="font-family: 'Playfair Display', serif;"
        >
            <span>ILIRSKI</span>
            <span>BUKVAR</span>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap');
</style>
