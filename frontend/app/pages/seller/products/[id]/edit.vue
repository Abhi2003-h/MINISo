<script setup lang="ts">
import { ref } from 'vue'
import type { Product } from '~/types/product'
import { apiFetch } from '~/utils/api'
import { formatPrice } from '~/utils/formatPrice'

definePageMeta({
  middleware: 'seller',
})

const route = useRoute()

const product = ref<Product | null>(null)
const price = ref('')

useHead({ title: 'Edit product' })

const toast = useAppToast()

const loading = ref(false)
const loadingProduct = ref(true)
const error = ref('')

interface ProductResponse {
  data: Product
}

async function loadProduct() {
  loadingProduct.value = true
  error.value = ''

  try {
    const response = await apiFetch<ProductResponse>(
        `/product/${route.params.id}`
    )

    product.value = response.data
    price.value = String(response.data.price)
  }
  catch (err) {
    error.value = 'Failed to load product.'
  }
  finally {
    loadingProduct.value = false
  }
}

async function updateProduct() {
  if (!product.value) {
    return
  }

  error.value = ''

  const newPrice = Number(price.value)

  if (
      price.value === '' ||
      !Number.isInteger(newPrice) ||
      newPrice < 0
  ) {
    error.value = 'Price must be a whole number greater than or equal to 0.'
    return
  }

  loading.value = true

  try {
    await apiFetch(`/product/${product.value.id}`, {
      method: 'PUT',
      body: {
        price: newPrice,
      },
    })

    toast.success('Price updated')

    await loadProduct()
  }
  catch (err: any) {
    if (err?.response?.status === 403) {
      error.value = 'You are not allowed to update this product.'
    }
    else if (err?.response?.status === 422) {
      error.value = 'Please enter a valid price.'
    }
    else {
      error.value = 'Failed to update product.'
    }
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  loadProduct()
})
</script>

<template>
  <div class="container-page max-w-xl py-8 sm:py-12">
    <NuxtLink
        to="/seller"
        class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition-colors hover:text-brand-700"
    >
      <AppIcon
          name="arrow-left"
          class="size-4"
      />
      Back to dashboard
    </NuxtLink>

    <PageHeader title="Edit product" />

    <LoadingSkeleton
        v-if="loadingProduct && !product"
        variant="list"
        :count="1"
    />

    <ErrorState
        v-else-if="!product"
        :message="error || 'We couldn\'t load this product.'"
        @retry="loadProduct"
    />

    <div
        v-else
        class="card p-5 sm:p-6"
    >
      <div class="flex items-center gap-4">
        <div class="size-14 shrink-0 overflow-hidden rounded-lg">
          <ProductImage
              :name="product.name"
              :seed="product.id"
          />
        </div>
        <div class="min-w-0">
          <h2 class="truncate text-base">
            {{ product.name }}
          </h2>
          <p class="mt-0.5 text-sm text-slate-500">
            Current price: {{ formatPrice(product.price) }}
          </p>
        </div>
      </div>

      <form
          class="mt-6 space-y-5 border-t border-slate-100 pt-6"
          @submit.prevent="updateProduct"
      >
        <p
            v-if="error"
            role="alert"
            class="alert-error"
        >
          {{ error }}
        </p>

        <div>
          <label
              for="price"
              class="label"
          >
            New price (in paise)
          </label>
          <input
              id="price"
              v-model="price"
              type="number"
              min="0"
              step="1"
              inputmode="numeric"
              class="input"
              required
          >
          <p class="hint">
            Whole number, e.g. 4900 = ₹49.00
          </p>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
          <NuxtLink
              to="/seller"
              class="btn btn-ghost"
          >
            Back
          </NuxtLink>
          <button
              type="submit"
              class="btn btn-primary"
              :disabled="loading"
          >
            {{ loading ? 'Updating...' : 'Update price' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
