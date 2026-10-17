<script setup lang="ts">
// Minimal brand mark: a blue geometric "M" tile + wordmark with a dot.
</script>

<template>
  <span class="inline-flex items-center gap-2.5">
    <svg
        viewBox="0 0 32 32"
        class="size-8 shrink-0"
        aria-hidden="true"
    >
      <rect width="32" height="32" rx="9" class="fill-brand-600" />
      <path
          d="M9 22V10.5l7 8 7-8V22"
          fill="none"
          stroke="white"
          stroke-width="2.6"
          stroke-linecap="round"
          stroke-linejoin="round"
      />
    </svg>
    <span class="text-lg font-semibold tracking-tight text-slate-900">
      MiniShop<span class="text-brand-600">.</span>
    </span>
  </span>
</template>
