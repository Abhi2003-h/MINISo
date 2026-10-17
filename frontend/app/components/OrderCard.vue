<script setup lang="ts">
import { formatPrice } from '~/utils/formatPrice'
import type { Order } from '~/types/order'

const props = defineProps<{
  order: Order
  defaultOpen?: boolean
}>()

const open = ref(props.defaultOpen ?? false)
const panelId = useId()

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const itemCount = computed(
  () => props.order.items.reduce((sum, item) => sum + item.quantity, 0),
)
</script>

<template>
  <article class="card overflow-hidden">
    <button
        type="button"
        class="flex w-full cursor-pointer flex-wrap items-center gap-x-6 gap-y-3 p-4 text-left transition-colors hover:bg-slate-50 sm:p-5"
        :aria-expanded="open"
        :aria-controls="panelId"
        @click="open = !open"
    >
      <div class="min-w-0 flex-1 basis-40">
        <div class="flex items-center gap-3">
          <h2 class="text-base">
            Order #{{ order.id }}
          </h2>
          <StatusBadge :status="order.status" />
        </div>
        <p class="mt-1 text-sm text-slate-500">
          {{ formatDate(order.created_at) }} · {{ itemCount }} {{ itemCount === 1 ? 'item' : 'items' }}
        </p>
      </div>

      <p class="text-base font-semibold tabular-nums text-slate-900">
        {{ formatPrice(order.total) }}
      </p>

      <AppIcon
          name="chevron-down"
          class="size-5 text-slate-400 transition-transform duration-200"
          :class="open && 'rotate-180'"
      />
    </button>

    <div
        v-show="open"
        :id="panelId"
        class="border-t border-slate-100 bg-surface px-4 py-2 sm:px-5"
    >
      <ul class="divide-y divide-slate-100">
        <li
            v-for="item in order.items"
            :key="item.id"
            class="flex items-center gap-3 py-3"
        >
          <div class="size-12 shrink-0 overflow-hidden rounded-lg">
            <ProductImage
                :name="item.product.name"
                :seed="item.product.id"
            />
          </div>

          <div class="min-w-0 flex-1">
            <NuxtLink
                :to="`/product/${item.product.id}`"
                class="block truncate text-sm font-medium text-slate-900 hover:text-brand-700"
            >
              {{ item.product.name }}
            </NuxtLink>
            <p class="text-xs text-slate-500">
              {{ formatPrice(item.price) }} × {{ item.quantity }}
            </p>
          </div>

          <p class="text-sm font-medium tabular-nums text-slate-900">
            {{ formatPrice(item.price * item.quantity) }}
          </p>
        </li>
      </ul>
    </div>
  </article>
</template>
