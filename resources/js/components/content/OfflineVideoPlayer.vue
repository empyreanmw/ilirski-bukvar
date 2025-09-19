<script setup lang="ts">
import 'vue-toast-notification/dist/theme-sugar.css';
import { Content } from '@/types';
import { useForm } from '@inertiajs/vue3';

interface Props {
    video: Content;
}
const props = withDefaults(defineProps<Props>(), {});
const openFile = (id: number): void => {
    const form = useForm({
        contentId: id,
    });

    form.post('/open-file', {
        preserveScroll: true,
        onSuccess: () => form.reset('contentId'),
    });
}
</script>

<template>
  <!-- Responsive 16:9 box that the image must fill -->
  <div
    class="thumb group"
    @click="openFile(video.id)"
  >
    <img
      :src="props.video.thumbnail_url"
      alt="Video thumbnail"
      class="thumb__img"
    />

    <!-- your button (unchanged colors) -->
    <div class="thumb__overlay">
      <div class="bg-white/80 dark:bg-gray-900/70 p-3 rounded-full transition group-hover:scale-110">
        <svg class="w-8 h-8 text-black-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M6.5 5.5v9l7-4.5-7-4.5Z" />
        </svg>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* The box */
.thumb{
  position: relative;
  width: 100%;
  border-radius: 0.5rem;          /* = rounded-lg */
  overflow: hidden;
  cursor: pointer;
  /* Use native aspect-ratio (modern browsers) */
  aspect-ratio: 16 / 9;
}
/* Fallback for older browsers (keeps 16:9) */
.thumb::before{
  content:"";
  display:block;
  padding-top:56.25%;
}
.thumb > *{                     /* place children on top of the ratio shim */
  position:absolute;
  inset:0;
}

/* The image MUST fill and crop as needed */
.thumb__img{
  width:100%;
  height:100%;
  object-fit:cover;              /* key line */
  object-position:center;
  display:block;                 /* avoid inline gaps */
}

/* Overlay for the button */
.thumb__overlay{
  display:flex;
  align-items:center;
  justify-content:center;
  pointer-events:none;           /* click passes to parent */
}
</style>