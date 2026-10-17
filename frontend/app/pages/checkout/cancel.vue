<script setup lang="ts">
useHead({ title: 'Payment cancelled' })
</script>

<template>
  <div class="container-page flex flex-col items-center py-16 text-center sm:py-24">
    <span class="inline-flex size-20 items-center justify-center rounded-full bg-slate-100 text-slate-500">
      <AppIcon
          name="cart"
          class="size-9"
      />
    </span>

    <h1 class="mt-8 text-2xl sm:text-3xl">
      Payment cancelled
    </h1>

    <p class="mt-3 max-w-sm text-slate-600">
      No payment was completed, and you haven't been charged.
      Your cart is still safe.
    </p>

    <div class="mt-8 flex w-full max-w-xs flex-col gap-3">
      <NuxtLink
          to="/cart"
          class="btn btn-primary btn-lg"
      >
        Return to cart
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
