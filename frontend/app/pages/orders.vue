<script setup lang="ts">

definePageMeta({
  middleware: 'auth',
})

import { onMounted, ref } from 'vue'
import { apiFetch } from '~/utils/api'
import type { OrdersResponse, Order } from '~/types/order'

useHead({ title: 'Your orders' })

const orders = ref<Order[]>([])
const loading = ref(true)
const error = ref(false)

async function loadOrders() {
  loading.value = true
  error.value = false

  try {
    const response = await apiFetch<OrdersResponse>('/orders')

    orders.value = response.orders
  }
  catch (err) {
    error.value = true
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  loadOrders()
})
</script>

<template>
  <div class="container-page max-w-4xl py-8 sm:py-12">
    <PageHeader
        title="Your orders"
        description="Track and review everything you've ordered."
    />

    <LoadingSkeleton
        v-if="loading"
        variant="orders"
    />

    <ErrorState
        v-else-if="error"
        message="We couldn't load your orders."
        @retry="loadOrders"
    />

    <EmptyState
        v-else-if="orders.length === 0"
        icon="package"
        title="No orders yet"
        description="When you place an order, it will show up here."
        action-label="Start shopping"
        action-to="/products"
    />

    <ul
        v-else
        class="space-y-4"
    >
      <li
          v-for="(order, index) in orders"
          :key="order.id"
      >
        <OrderCard
            :order="order"
            :default-open="index === 0"
        />
      </li>
    </ul>
  </div>
</template>
