<script setup lang="ts">
import type { Product } from '~/types/product'
import { apiFetch } from '~/utils/api'
import { formatPrice } from '~/utils/formatPrice'

const route = useRoute()

const productId = route.params.id

const product = ref<Product | null>(null)
const loadingProduct = ref(true)
const loadError = ref<'notFound' | 'failed' | null>(null)
const quantity = ref(1)
const addedToCart = ref(false)

const { addToCart, addingId } = useAddToCart()
const addingToCart = computed(() => addingId.value !== null)

interface ProductResponse {
  data: Product
}

async function loadProduct() {
  loadingProduct.value = true
  loadError.value = null

  try {
    const response = await apiFetch<ProductResponse>(`/product/${productId}`)
    product.value = response.data
  }
  catch (err: any) {
    loadError.value = err?.response?.status === 404 ? 'notFound' : 'failed'
  }
  finally {
    loadingProduct.value = false
  }
}

async function handleAddToCart() {
  if (!product.value) {
    return
  }

  addedToCart.value = false

  const added = await addToCart(product.value, quantity.value)

  if (added) {
    addedToCart.value = true
  }
}

const stockStatus = computed(() => {
  const stock = product.value?.stock ?? 0

  if (stock <= 0) {
    return { label: 'Out of stock', dot: 'bg-slate-400', text: 'text-slate-500' }
  }
  if (stock <= 5) {
    return { label: `Only ${stock} left`, dot: 'bg-amber-500', text: 'text-amber-700' }
  }
  return { label: 'In stock', dot: 'bg-emerald-500', text: 'text-emerald-700' }
})

useHead(() => ({ title: product.value?.name ?? 'Product' }))

onMounted(() => {
  loadProduct()
})
</script>

<template>
  <div class="container-page py-6 sm:py-10">
    <LoadingSkeleton
        v-if="loadingProduct"
        variant="product"
    />

    <EmptyState
        v-else-if="loadError === 'notFound'"
        icon="package"
        title="Product not found"
        description="This product may have been removed."
        action-label="Browse products"
        action-to="/products"
    />

    <ErrorState
        v-else-if="loadError"
        message="We couldn't load this product."
        @retry="loadProduct"
    />

    <template v-else-if="product">
      <!-- Breadcrumbs -->
      <nav
          aria-label="Breadcrumb"
          class="mb-6 text-sm text-slate-500"
      >
        <ol class="flex flex-wrap items-center gap-1.5">
          <li>
            <NuxtLink
                to="/"
                class="transition-colors hover:text-brand-700"
            >
              Home
            </NuxtLink>
          </li>
          <li aria-hidden="true">
            <AppIcon
                name="chevron-right"
                class="size-3.5"
            />
          </li>
          <li>
            <NuxtLink
                to="/products"
                class="transition-colors hover:text-brand-700"
            >
              Products
            </NuxtLink>
          </li>
          <li aria-hidden="true">
            <AppIcon
                name="chevron-right"
                class="size-3.5"
            />
          </li>
          <li
              class="max-w-[14rem] truncate font-medium text-slate-900"
              aria-current="page"
          >
            {{ product.name }}
          </li>
        </ol>
      </nav>

      <div class="grid gap-8 md:grid-cols-2 lg:gap-14">
        <div class="overflow-hidden rounded-xl border border-slate-200">
          <ProductImage
              :name="product.name"
              :seed="product.id"
          />
        </div>

        <div class="flex flex-col md:py-2">
          <NuxtLink
              v-if="product.category"
              :to="{ path: '/products', query: { category: product.category.id } }"
              class="w-fit text-sm font-medium text-brand-600 transition-colors hover:text-brand-700"
          >
            {{ product.category.name }}
          </NuxtLink>

          <h1 class="mt-2 text-3xl font-bold sm:text-4xl">
            {{ product.name }}
          </h1>

          <p class="mt-4 text-3xl font-semibold text-slate-900">
            {{ formatPrice(product.price) }}
          </p>

          <p
              class="mt-3 inline-flex items-center gap-2 text-sm font-medium"
              :class="stockStatus.text"
          >
            <span
                class="size-2 rounded-full"
                :class="stockStatus.dot"
            />
            {{ stockStatus.label }}
          </p>

          <p class="mt-6 whitespace-pre-line leading-7 text-slate-600">
            {{ product.description }}
          </p>

          <div class="mt-8 border-t border-slate-100 pt-8">
            <div class="flex items-center justify-between gap-4 sm:justify-start sm:gap-6">
              <span class="text-sm font-medium text-slate-700">Quantity</span>
              <QuantityStepper
                  :quantity="quantity"
                  :max="product.stock"
                  :disabled="product.stock <= 0"
                  @increase="quantity++"
                  @decrease="quantity--"
              />
            </div>

            <button
                type="button"
                class="btn btn-primary btn-lg mt-5 w-full sm:max-w-sm"
                :disabled="product.stock <= 0 || addingToCart"
                @click="handleAddToCart"
            >
              <AppIcon
                  name="cart"
                  class="size-5"
              />
              {{ product.stock <= 0 ? 'Out of Stock' : addingToCart ? 'Adding...' : 'Add to Cart' }}
            </button>

            <p
                v-if="addedToCart"
                class="mt-3 flex items-center gap-2 text-sm text-emerald-700"
                role="status"
            >
              <AppIcon
                  name="check"
                  class="size-4"
              />
              Added to your cart.
              <NuxtLink
                  to="/cart"
                  class="font-medium text-brand-600 underline-offset-2 hover:underline"
              >
                View cart
              </NuxtLink>
            </p>
          </div>

          <NuxtLink
              to="/products"
              class="mt-8 inline-flex w-fit items-center gap-2 text-sm font-medium text-slate-500 transition-colors hover:text-brand-700"
          >
            <AppIcon
                name="arrow-left"
                class="size-4"
            />
            Back to products
          </NuxtLink>
        </div>
      </div>
    </template>
  </div>
</template>
