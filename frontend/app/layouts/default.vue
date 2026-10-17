<script setup lang="ts">
import AppNav from '~/components/AppNav.vue'
</script>

<template>
  <div class="flex min-h-dvh flex-col">
    <a
        href="#main"
        class="sr-only z-50 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white focus:not-sr-only focus:fixed focus:left-4 focus:top-4"
    >
      Skip to content
    </a>

    <AppNav />

    <main
        id="main"
        class="flex-1"
    >
      <slot />
    </main>

    <AppFooter />
    <ToastHost />
  </div>
</template>
