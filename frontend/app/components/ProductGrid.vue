<script setup lang="ts">
import type { Product } from '~/types/product'

defineProps<{
  products: Product[]
  addingId?: number | null
}>()

const emit = defineEmits<{
  addToCart: [product: Product]
}>()
</script>

<template>
  <ul class="grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3 xl:grid-cols-4">
    <li
        v-for="product in products"
        :key="product.id"
    >
      <ProductCard
          :product="product"
          :adding="addingId === product.id"
          @add-to-cart="emit('addToCart', $event)"
      />
    </li>
  </ul>
</template>
