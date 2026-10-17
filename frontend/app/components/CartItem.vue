<script setup lang="ts">
import { formatPrice } from '~/utils/formatPrice'
import type { CartItem } from '~/types/cart'

defineProps<{
  item: CartItem
  disabled?: boolean
}>()

const emit = defineEmits<{
  increase: [item: CartItem]
  decrease: [item: CartItem]
  remove: [item: CartItem]
}>()
</script>

<template>
  <article class="flex gap-4 rounded-xl border border-slate-200 bg-white p-4">
    <NuxtLink
        :to="`/product/${item.product.id}`"
        class="block size-20 shrink-0 overflow-hidden rounded-lg sm:size-24"
        :aria-label="`View ${item.product.name}`"
        tabindex="-1"
    >
      <ProductImage
          :name="item.product.name"
          :seed="item.product.id"
      />
    </NuxtLink>

    <div class="flex min-w-0 flex-1 flex-col">
      <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
          <h2 class="truncate text-[15px] font-medium text-slate-900">
            <NuxtLink
                :to="`/product/${item.product.id}`"
                class="rounded hover:text-brand-700"
            >
              {{ item.product.name }}
            </NuxtLink>
          </h2>
          <p class="mt-0.5 text-sm text-slate-500">
            {{ formatPrice(item.product.price) }} each
          </p>
          <p
              v-if="item.quantity >= item.product.stock"
              class="mt-0.5 text-xs text-amber-600"
          >
            Max stock reached ({{ item.product.stock }} available)
          </p>
        </div>

        <p class="shrink-0 text-base font-semibold tabular-nums text-slate-900">
          {{ formatPrice(item.product.price * item.quantity) }}
        </p>
      </div>

      <div class="mt-auto flex items-center justify-between gap-3 pt-3">
        <QuantityStepper
            :quantity="item.quantity"
            :max="item.product.stock"
            :disabled="disabled"
            :label="`Quantity for ${item.product.name}`"
            @increase="emit('increase', item)"
            @decrease="emit('decrease', item)"
        />

        <button
            type="button"
            class="btn btn-ghost btn-sm text-slate-500 hover:!text-red-600"
            :disabled="disabled"
            :aria-label="`Remove ${item.product.name} from cart`"
            @click="emit('remove', item)"
        >
          <AppIcon
              name="trash"
              class="size-4"
          />
          <span class="hidden sm:inline">Remove</span>
        </button>
      </div>
    </div>
  </article>
</template>
