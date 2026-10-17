<script setup lang="ts">
import type { Product } from '~/types/product'
import { apiFetch } from '~/utils/api'
import { formatPrice } from '~/utils/formatPrice'

definePageMeta({
  middleware: 'seller',
})

interface ProductsResponse {
  data: Product[]
}

const products = ref<Product[]>([])
useHead({ title: 'Seller dashboard' })

const toast = useAppToast()

const loading = ref(true)
const error = ref('')

async function loadProducts() {
  loading.value = true
  error.value = ''

  try {
    const response = await apiFetch<ProductsResponse>('/seller/products')
    products.value = response.data
  }
  catch (err) {
    error.value = 'Failed to load products.'
  }
  finally {
    loading.value = false
  }
}

async function deleteProduct(product: Product) {
  const confirmed = window.confirm(
      `Are you sure you want to delete "${product.name}"?`
  )

  if (!confirmed) {
    return
  }

  loading.value = true
  error.value = ''

  try {
    await apiFetch(`/product/${product.id}`, {
      method: 'DELETE',
    })

    toast.success('Product deleted')

    await loadProducts()
  }
  catch (err) {
    error.value = 'Failed to delete product.'
    toast.error('Failed to delete product.')
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  loadProducts()
})
</script>

<template>
  <div class="container-page max-w-4xl py-8 sm:py-12">
    <PageHeader
        title="Seller dashboard"
        description="Manage the products in your shop."
    >
      <NuxtLink
          to="/seller/products/create"
          class="btn btn-primary"
      >
        <AppIcon
            name="plus"
            class="size-4"
        />
        Add product
      </NuxtLink>
    </PageHeader>

    <LoadingSkeleton
        v-if="loading && products.length === 0 && !error"
        variant="list"
        :count="4"
    />

    <ErrorState
        v-else-if="error && products.length === 0"
        :message="error"
        :loading="loading"
        @retry="loadProducts"
    />

    <EmptyState
        v-else-if="products.length === 0"
        icon="bag"
        title="Your shop is empty"
        description="Add your first product and it will appear in the store."
        action-label="Add your first product"
        action-to="/seller/products/create"
    />

    <ul
        v-else
        class="divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white"
    >
      <li
          v-for="product in products"
          :key="product.id"
          class="flex flex-wrap items-center gap-x-4 gap-y-3 p-4"
      >
        <div class="size-14 shrink-0 overflow-hidden rounded-lg">
          <ProductImage
              :name="product.name"
              :seed="product.id"
          />
        </div>

        <div class="min-w-0 flex-1 basis-48">
          <NuxtLink
              :to="`/product/${product.id}`"
              class="block truncate font-medium text-slate-900 hover:text-brand-700"
          >
            {{ product.name }}
          </NuxtLink>
          <p class="mt-0.5 text-sm text-slate-500">
            {{ product.category?.name ?? 'Uncategorized' }}
            ·
            <span :class="product.stock <= 0 ? 'text-red-600' : product.stock <= 5 ? 'text-amber-600' : ''">
              {{ product.stock <= 0 ? 'Out of stock' : `${product.stock} in stock` }}
            </span>
          </p>
        </div>

        <p class="font-semibold tabular-nums text-slate-900">
          {{ formatPrice(product.price) }}
        </p>

        <div class="flex gap-2">
          <NuxtLink
              :to="`/seller/products/${product.id}/edit`"
              class="btn btn-secondary btn-sm"
          >
            <AppIcon
                name="pencil"
                class="size-3.5"
            />
            Edit
          </NuxtLink>

          <button
              type="button"
              class="btn btn-danger btn-sm"
              :disabled="loading"
              :aria-label="`Delete ${product.name}`"
              @click="deleteProduct(product)"
          >
            <AppIcon
                name="trash"
                class="size-3.5"
            />
            Delete
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>
