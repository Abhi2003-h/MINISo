<script setup lang="ts">

definePageMeta({
  middleware: 'auth',
})

import type { Cart, CartItem as CartItemType, CartResponse } from '~/types/cart'
import { apiFetch } from '~/utils/api'
import { formatPrice } from '~/utils/formatPrice'

useHead({ title: 'Your cart' })

const toast = useAppToast()
const cartCount = useCartCount()

const cart = ref<Cart | null>(null)
const total = ref(0)

const initialLoading = ref(true)
const loading = ref(false)
const loadError = ref(false)
const checkoutLoading = ref(false)

const itemCount = computed(
  () => cart.value?.items.reduce((sum, item) => sum + item.quantity, 0) ?? 0,
)

async function loadCart() {
  loading.value = true
  loadError.value = false
  try {
    const response = await apiFetch<CartResponse>('/cart')

    cart.value = response.cart
    total.value = response.total
    cartCount.setFromItems(response.cart.items)
  } catch (err) {
    loadError.value = true
  } finally {
    loading.value = false
    initialLoading.value = false
  }
}

onMounted(() => {
  loadCart()
})

async function increaseQuantity(item: CartItemType) {
  if (item.quantity >= item.product.stock) {
    toast.error('Not enough stock available.')
    return
  }

  loading.value = true

  try {
    const newQuantity = item.quantity + 1

    await apiFetch(`/cart/items/${item.id}`, {
      method: 'PUT',
      body: {
        quantity: newQuantity,
      },
    })

    await loadCart()
  } catch (err) {
    toast.error('Failed to update quantity.')
  } finally {
    loading.value = false
  }
}
async function decreaseQuantity(item: CartItemType) {
  loading.value = true

  try {
    const newQuantity = item.quantity - 1

    await apiFetch(`/cart/items/${item.id}`, {
      method: 'PUT',
      body: {
        quantity: newQuantity,
      },
    })

    await loadCart()
  } catch (err) {
    toast.error('Failed to decrease quantity.')
  } finally {
    loading.value = false
  }
}
async function removeItem(item: CartItemType) {
  loading.value = true

  try {
    await apiFetch(`/cart/items/${item.id}`, {
      method: 'DELETE',
    })

    toast.success(`${item.product.name} removed`)

    await loadCart()
  } catch (err) {
    toast.error('Failed to remove item.')
  } finally {
    loading.value = false
  }
}
async function checkout() {
  checkoutLoading.value = true

  try {
    const response = await apiFetch<{
      url: string
    }>('/checkout/session', {
      method: 'POST',
    })

    toast.info('Redirecting to secure checkout...')

    window.location.href = response.url
  }
  catch (err: any) {
    toast.error(
        err?.data?.message ||
        'Unable to start checkout.'
    )
    checkoutLoading.value = false
  }
}

</script>

<template>
  <div class="container-page py-8 sm:py-12">
    <PageHeader
        title="Your cart"
        :description="itemCount > 0 ? `${itemCount} ${itemCount === 1 ? 'item' : 'items'} ready for checkout` : undefined"
    />

    <LoadingSkeleton
        v-if="initialLoading"
        variant="cart"
    />

    <ErrorState
        v-else-if="loadError && !cart"
        message="We couldn't load your cart."
        :loading="loading"
        @retry="loadCart"
    />

    <div
        v-else-if="cart && cart.items.length > 0"
        class="grid items-start gap-8 lg:grid-cols-[1fr_22rem]"
    >
      <ul class="space-y-3">
        <li
            v-for="item in cart.items"
            :key="item.id"
        >
          <CartItem
              :item="item"
              :disabled="loading"
              @increase="increaseQuantity"
              @decrease="decreaseQuantity"
              @remove="removeItem"
          />
        </li>
      </ul>

      <aside
          aria-labelledby="summary-heading"
          class="rounded-xl border border-brand-100 bg-brand-50/60 p-5 sm:p-6 lg:sticky lg:top-24"
      >
        <h2
            id="summary-heading"
            class="text-base"
        >
          Order summary
        </h2>

        <dl class="mt-5 space-y-3 text-sm">
          <div class="flex justify-between text-slate-600">
            <dt>Items ({{ itemCount }})</dt>
            <dd class="tabular-nums">
              {{ formatPrice(total) }}
            </dd>
          </div>
          <div class="flex items-baseline justify-between border-t border-brand-100 pt-4">
            <dt class="font-medium text-slate-900">
              Total
            </dt>
            <dd class="text-2xl font-semibold tabular-nums text-slate-900">
              {{ formatPrice(total) }}
            </dd>
          </div>
        </dl>

        <button
            type="button"
            class="btn btn-primary btn-lg mt-6 w-full"
            :disabled="loading || checkoutLoading"
            @click="checkout"
        >
          <AppIcon
              name="lock"
              class="size-4"
          />
          {{ checkoutLoading ? 'Redirecting...' : 'Checkout' }}
        </button>

        <p class="mt-3 text-center text-xs text-slate-500">
          Secure payment powered by Stripe
        </p>

        <NuxtLink
            to="/products"
            class="mt-4 block text-center text-sm font-medium text-brand-600 transition-colors hover:text-brand-700"
        >
          Continue shopping
        </NuxtLink>
      </aside>
    </div>

    <EmptyState
        v-else
        icon="cart"
        title="Your cart is empty"
        description="Discover something you'll love."
        action-label="Browse products"
        action-to="/products"
    />
  </div>
</template>
