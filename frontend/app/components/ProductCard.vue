<script setup lang="ts">
import { formatPrice } from '~/utils/formatPrice'
import type { Product } from '~/types/product'

defineProps<{
  product: Product
  adding?: boolean
}>()

const emit = defineEmits<{
  addToCart: [product: Product]
}>()

</script>

<template>
  <article
      class="group relative flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition duration-200 hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-sm"
  >
    <div class="relative overflow-hidden">
      <ProductImage
          :name="product.name"
          :seed="product.id"
          class="transition-transform duration-300 ease-out group-hover:scale-105"
      />

      <span
          v-if="product.stock <= 0"
          class="absolute left-2.5 top-2.5 rounded-full bg-white/90 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200"
      >
        Out of stock
      </span>
    </div>

    <div class="flex flex-1 flex-col p-3.5 sm:p-4">
      <p class="truncate text-xs font-medium text-slate-500">
        {{ product.category?.name ?? 'Uncategorized' }}
      </p>

      <h2 class="mt-1 line-clamp-2 min-h-10 text-sm font-medium leading-5 text-slate-900 sm:text-[15px]">
        <NuxtLink
            :to="`/product/${product.id}`"
            class="rounded after:absolute after:inset-0 after:content-['']"
        >
          {{ product.name }}
        </NuxtLink>
      </h2>

      <p
          v-if="product.stock > 0 && product.stock <= 5"
          class="mt-1 text-xs font-medium text-amber-600"
      >
        Only {{ product.stock }} left
      </p>

      <div class="mt-auto flex items-center justify-between gap-2 pt-3">
        <p class="text-base font-semibold text-slate-900">
          {{ formatPrice(product.price) }}
        </p>

        <button
            type="button"
            class="btn btn-secondary btn-sm relative z-10 !px-2.5 transition-colors group-hover:border-brand-600 group-hover:bg-brand-600 group-hover:text-white sm:!px-3"
            :disabled="product.stock <= 0 || adding"
            :aria-label="`Add ${product.name} to cart`"
            @click="emit('addToCart', product)"
        >
          <AppIcon
              :name="adding ? 'check' : 'plus'"
              class="size-4"
          />
          <span class="hidden sm:inline">{{ adding ? 'Adding' : 'Cart' }}</span>
        </button>
      </div>
    </div>
  </article>
</template>
