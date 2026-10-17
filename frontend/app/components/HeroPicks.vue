<script setup lang="ts">
import { formatPrice } from '~/utils/formatPrice'
import type { Product } from '~/types/product'

const props = defineProps<{
  products: Product[]
}>()

// "MiniShop Picks": up to three real products floating gently.
// Falls back to quiet placeholder cards when there are none yet.
const picks = computed(() => props.products.slice(0, 3))

const positions = [
  'left-0 top-6',
  'right-0 top-[16%]',
  'bottom-0 left-[26%] hidden sm:block',
]
const delays = ['0s', '-2.4s', '-4.6s']
</script>

<template>
  <div
      class="relative mx-auto h-72 w-full max-w-lg sm:h-[28rem]"
      aria-label="MiniShop Picks"
      role="group"
  >
    <div
        class="dot-grid absolute -right-2 -top-2 size-24 opacity-70"
        aria-hidden="true"
    />
    <div
        class="absolute inset-x-6 inset-y-8 rounded-[2rem] bg-brand-50"
        aria-hidden="true"
    />

    <span class="absolute left-1/2 top-0 z-10 -translate-x-1/2 rounded-full border border-brand-100 bg-white px-3 py-1 text-xs font-medium text-brand-700">
      MiniShop Picks
    </span>

    <template v-if="picks.length">
      <NuxtLink
          v-for="(product, index) in picks"
          :key="product.id"
          :to="`/product/${product.id}`"
          class="absolute w-36 animate-float overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-colors hover:border-brand-300 sm:w-48"
          :class="positions[index]"
          :style="{ animationDelay: delays[index] }"
      >
        <ProductImage
            :name="product.name"
            :seed="product.id"
            class="!aspect-[4/3]"
        />
        <div class="p-3">
          <p class="truncate text-sm font-medium text-slate-900">
            {{ product.name }}
          </p>
          <p class="mt-0.5 text-sm font-semibold text-brand-600">
            {{ formatPrice(product.price) }}
          </p>
        </div>
      </NuxtLink>
    </template>

    <template v-else>
      <div
          v-for="(position, index) in positions"
          :key="index"
          class="absolute w-36 animate-float overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm sm:w-48"
          :class="position"
          :style="{ animationDelay: delays[index] }"
          aria-hidden="true"
      >
        <ProductImage
            name="MiniShop"
            :seed="index"
            class="!aspect-[4/3]"
        />
        <div class="space-y-2 p-3">
          <div class="h-2.5 w-3/4 rounded bg-slate-100" />
          <div class="h-2.5 w-1/3 rounded bg-brand-100" />
        </div>
      </div>
    </template>
  </div>
</template>
