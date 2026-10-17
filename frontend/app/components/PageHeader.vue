<script setup lang="ts">
defineProps<{
  title: string
  description?: string
}>()
</script>

<template>
  <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-2xl sm:text-3xl">
        {{ title }}
      </h1>
      <p
          v-if="description"
          class="mt-1.5 text-sm text-slate-500 sm:text-base"
      >
        {{ description }}
      </p>
    </div>
    <div
        v-if="$slots.default"
        class="flex items-center gap-3"
    >
      <slot />
    </div>
  </div>
</template>
