<script setup lang="ts">
const props = defineProps<{
  status: string
}>()

const styles = computed(() => {
  switch (props.status.toLowerCase()) {
    case 'paid':
    case 'completed':
    case 'delivered':
      return 'bg-emerald-50 text-emerald-700 ring-emerald-600/15'
    case 'pending':
    case 'processing':
    case 'shipped':
      return 'bg-brand-50 text-brand-700 ring-brand-600/15'
    case 'failed':
    case 'cancelled':
    case 'canceled':
      return 'bg-red-50 text-red-700 ring-red-600/15'
    default:
      return 'bg-slate-100 text-slate-600 ring-slate-500/10'
  }
})
</script>

<template>
  <span
      class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide ring-1 ring-inset"
      :class="styles"
  >
    {{ status }}
  </span>
</template>
