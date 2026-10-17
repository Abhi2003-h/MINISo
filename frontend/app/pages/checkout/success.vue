<script setup lang="ts">
const route = useRoute()

useHead({ title: 'Payment successful' })

const sessionId = computed(() => {
  return typeof route.query.session_id === 'string'
      ? route.query.session_id
      : null
})

const reference = computed(() => sessionId.value?.slice(-8).toUpperCase() ?? null)

// The paid order empties the cart; refresh the header badge.
const { refresh: refreshCart } = useCartCount()
onMounted(refreshCart)
</script>

<template>
  <div class="container-page flex flex-col items-center py-16 text-center sm:py-24">
    <span class="relative inline-flex size-20 animate-ring items-center justify-center rounded-full bg-brand-50 opacity-0">
      <svg
          viewBox="0 0 24 24"
          class="size-10 text-brand-600"
          fill="none"
          stroke="currentColor"
          stroke-width="2.4"
          stroke-linecap="round"
          stroke-linejoin="round"
          aria-hidden="true"
      >
        <path
            d="M5 12.5l4.5 4.5L19 7.5"
            class="animate-draw"
            style="stroke-dasharray: 24; stroke-dashoffset: 24;"
        />
      </svg>
    </span>

    <h1 class="mt-8 text-2xl sm:text-3xl">
      Payment successful
    </h1>

    <p class="mt-3 max-w-sm text-slate-600">
      Thank you! Your order has been confirmed and we're getting it ready.
    </p>

    <p
        v-if="reference"
        class="mt-4 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600"
    >
      Reference {{ reference }}
    </p>

    <div class="mt-8 flex w-full max-w-xs flex-col gap-3">
      <NuxtLink
          to="/orders"
          class="btn btn-primary btn-lg"
      >
        View orders
      </NuxtLink>
      <NuxtLink
          to="/products"
          class="btn btn-secondary btn-lg"
      >
        Continue shopping
      </NuxtLink>
    </div>
  </div>
</template>
