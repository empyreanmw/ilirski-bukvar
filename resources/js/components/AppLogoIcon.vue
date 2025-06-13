<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();

// Import the PNG image
import logoPng from '@/assets/logo.png';
</script>

<template>
    <div class="flex items-center gap-2 bg-gray-100 rounded-xl">
        <img :src="logoPng" alt="Book Logo" class="w-10 h-12" />
        <div class="flex flex-col leading-[1.0] font-black text-lg" style="font-family: 'Cinzel', serif; color: #4f4739;">
            <span>ILIRSKI</span>
            <span>BUKVAR</span>
        </div>

    </div>
</template>
