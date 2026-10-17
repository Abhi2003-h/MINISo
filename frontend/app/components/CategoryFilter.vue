<script setup lang="ts">
const model = defineModel<string>({ default: 'all' })

defineProps<{
  categories: { id: number, name: string }[]
}>()

function chipClass(active: boolean) {
  return [
    'inline-flex h-10 shrink-0 cursor-pointer items-center rounded-full border px-4 text-sm font-medium transition-colors sm:h-9',
    active
      ? 'border-brand-600 bg-brand-600 text-white'
      : 'border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-700',
  ]
}
</script>

<template>
  <div
      role="group"
      aria-label="Filter by category"
      class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:overflow-visible sm:px-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
  >
    <button
        type="button"
        :class="chipClass(model === 'all')"
        :aria-pressed="model === 'all'"
        @click="model = 'all'"
    >
      All
    </button>
    <button
        v-for="category in categories"
        :key="category.id"
        type="button"
        :class="chipClass(model === String(category.id))"
        :aria-pressed="model === String(category.id)"
        @click="model = String(category.id)"
    >
      {{ category.name }}
    </button>
  </div>
</template>
