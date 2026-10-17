<script setup lang="ts">
// Products have no image field yet, so we render an intentional placeholder:
// a soft tinted tile with the product initial and a tiny brand dot-grid.
const props = defineProps<{
  name: string
  seed?: number
}>()

const tints = [
  'bg-brand-50',
  'bg-slate-100',
  'bg-sky-50',
  'bg-indigo-50',
]

const tint = computed(() => tints[Math.abs(props.seed ?? 0) % tints.length])
const initial = computed(() => props.name.trim().charAt(0).toUpperCase() || 'M')
</script>

<template>
  <div
      role="img"
      :aria-label="`${name} product image`"
      class="@container relative flex aspect-square w-full items-center justify-center overflow-hidden"
      :class="tint"
  >
    <div
        class="dot-grid absolute right-3 top-3 size-12 opacity-60"
        aria-hidden="true"
    />
    <div
        class="flex size-[38%] items-center justify-center rounded-2xl bg-white text-[18cqw] font-semibold leading-none text-brand-600 shadow-sm ring-1 ring-slate-900/5"
        aria-hidden="true"
    >
      {{ initial }}
    </div>
  </div>
</template>
