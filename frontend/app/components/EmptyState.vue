<script setup lang="ts">
import type { IconName } from './AppIcon.vue'

withDefaults(defineProps<{
  icon?: IconName
  title: string
  description?: string
  actionLabel?: string
  actionTo?: string
}>(), {
  icon: 'bag',
})
</script>

<template>
  <div class="mx-auto flex max-w-sm flex-col items-center px-4 py-16 text-center">
    <div class="relative">
      <span
          class="dot-grid absolute -right-5 -top-3 size-10 opacity-70"
          aria-hidden="true"
      />
      <span class="relative inline-flex size-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
        <AppIcon
            :name="icon"
            class="size-7"
        />
      </span>
    </div>

    <h2 class="mt-6 text-lg">
      {{ title }}
    </h2>
    <p
        v-if="description"
        class="mt-2 text-sm text-slate-500"
    >
      {{ description }}
    </p>

    <div class="mt-6">
      <slot>
        <NuxtLink
            v-if="actionLabel && actionTo"
            :to="actionTo"
            class="btn btn-primary"
        >
          {{ actionLabel }}
        </NuxtLink>
      </slot>
    </div>
  </div>
</template>
