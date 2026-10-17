<script setup lang="ts">
withDefaults(defineProps<{
  variant?: 'products' | 'cart' | 'orders' | 'product' | 'list'
  count?: number
}>(), {
  variant: 'products',
  count: 8,
})
</script>

<template>
  <div
      aria-busy="true"
      role="status"
  >
    <span class="sr-only">Loading...</span>

    <!-- Product grid -->
    <ul
        v-if="variant === 'products'"
        class="grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3 xl:grid-cols-4"
    >
      <li
          v-for="n in count"
          :key="n"
          class="overflow-hidden rounded-xl border border-slate-200"
      >
        <div class="skeleton aspect-square !rounded-none" />
        <div class="space-y-2.5 p-3.5 sm:p-4">
          <div class="skeleton h-3 w-1/3" />
          <div class="skeleton h-4 w-4/5" />
          <div class="flex items-center justify-between pt-2">
            <div class="skeleton h-5 w-16" />
            <div class="skeleton h-8 w-14" />
          </div>
        </div>
      </li>
    </ul>

    <!-- Cart rows + summary -->
    <div
        v-else-if="variant === 'cart'"
        class="grid gap-8 lg:grid-cols-[1fr_22rem]"
    >
      <div class="space-y-4">
        <div
            v-for="n in 3"
            :key="n"
            class="flex gap-4 rounded-xl border border-slate-200 p-4"
        >
          <div class="skeleton size-20 shrink-0 sm:size-24" />
          <div class="flex-1 space-y-3">
            <div class="skeleton h-4 w-2/3" />
            <div class="skeleton h-3 w-1/4" />
            <div class="skeleton h-9 w-28" />
          </div>
        </div>
      </div>
      <div class="skeleton h-56 rounded-xl" />
    </div>

    <!-- Order cards -->
    <div
        v-else-if="variant === 'orders'"
        class="space-y-4"
    >
      <div
          v-for="n in 3"
          :key="n"
          class="space-y-3 rounded-xl border border-slate-200 p-5"
      >
        <div class="flex justify-between">
          <div class="skeleton h-5 w-28" />
          <div class="skeleton h-5 w-16" />
        </div>
        <div class="skeleton h-3 w-40" />
      </div>
    </div>

    <!-- Product detail -->
    <div
        v-else-if="variant === 'product'"
        class="grid gap-8 md:grid-cols-2 lg:gap-14"
    >
      <div class="skeleton aspect-square !rounded-xl" />
      <div class="space-y-4 py-2">
        <div class="skeleton h-3 w-24" />
        <div class="skeleton h-8 w-3/4" />
        <div class="skeleton h-7 w-28" />
        <div class="space-y-2 pt-4">
          <div class="skeleton h-3 w-full" />
          <div class="skeleton h-3 w-full" />
          <div class="skeleton h-3 w-2/3" />
        </div>
        <div class="skeleton mt-8 h-12 w-full" />
      </div>
    </div>

    <!-- Generic list rows -->
    <div
        v-else
        class="divide-y divide-slate-100 rounded-xl border border-slate-200"
    >
      <div
          v-for="n in count"
          :key="n"
          class="flex items-center gap-4 p-4"
      >
        <div class="skeleton size-14 shrink-0" />
        <div class="flex-1 space-y-2">
          <div class="skeleton h-4 w-1/2" />
          <div class="skeleton h-3 w-1/4" />
        </div>
      </div>
    </div>
  </div>
</template>
