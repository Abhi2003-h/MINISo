<script setup lang="ts">
withDefaults(defineProps<{
  quantity: number
  min?: number
  max?: number
  disabled?: boolean
  label?: string
}>(), {
  min: 1,
  label: 'Quantity',
})

const emit = defineEmits<{
  increase: []
  decrease: []
}>()
</script>

<template>
  <div
      role="group"
      :aria-label="label"
      class="inline-flex h-11 items-center rounded-lg border border-slate-200 bg-white sm:h-10"
  >
    <button
        type="button"
        class="inline-flex size-11 cursor-pointer items-center justify-center rounded-l-lg text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900 disabled:pointer-events-none disabled:opacity-40 sm:size-10"
        aria-label="Decrease quantity"
        :disabled="disabled || quantity <= min"
        @click="emit('decrease')"
    >
      <AppIcon
          name="minus"
          class="size-4"
      />
    </button>

    <span
        class="min-w-8 text-center text-sm font-medium tabular-nums text-slate-900"
        aria-live="polite"
    >
      {{ quantity }}
    </span>

    <button
        type="button"
        class="inline-flex size-11 cursor-pointer items-center justify-center rounded-r-lg text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900 disabled:pointer-events-none disabled:opacity-40 sm:size-10"
        aria-label="Increase quantity"
        :disabled="disabled || (max !== undefined && quantity >= max)"
        @click="emit('increase')"
    >
      <AppIcon
          name="plus"
          class="size-4"
      />
    </button>
  </div>
</template>
