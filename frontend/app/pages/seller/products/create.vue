<script setup lang="ts">
import { ref } from 'vue'
import { apiFetch } from '~/utils/api'
import type { Category } from '~/types/product'

definePageMeta({
  middleware: 'seller',
})



interface CategoriesResponse {
  categories: Category[]
}

const name = ref('')
const description = ref('')
const price = ref('')
const stock = ref('')
const categoryId = ref('')

const categories = ref<Category[]>([])

useHead({ title: 'Add product' })

const toast = useAppToast()

const loading = ref(false)
const loadingCategories = ref(false)
const error = ref('')

const pricePreview = computed(() => {
  const value = Number(price.value)

  return price.value !== '' && Number.isInteger(value) && value >= 0
    ? formatPrice(value)
    : null
})

async function loadCategories() {
  loadingCategories.value = true
  error.value = ''

  try {
    const response = await apiFetch<CategoriesResponse>('/categories')
    categories.value = response.categories
  }
  catch (err) {
    error.value = 'Failed to load categories.'
  }
  finally {
    loadingCategories.value = false
  }
}

async function createProduct() {
  error.value = ''

  const productName = name.value.trim()
  const productDescription = description.value.trim()
  const productPrice = Number(price.value)
  const productStock = Number(stock.value)
  const productCategoryId = Number(categoryId.value)

  if (!productName) {
    error.value = 'Product name is required.'
    return
  }

  if (!productDescription) {
    error.value = 'Product description is required.'
    return
  }

  if (price.value === '' || !Number.isInteger(productPrice) || productPrice < 0) {
    error.value = 'Price must be a whole number greater than or equal to 0.'
    return
  }

  if (stock.value === '' || !Number.isInteger(productStock) || productStock < 0) {
    error.value = 'Stock must be a whole number greater than or equal to 0.'
    return
  }

  if (!categoryId.value || !Number.isInteger(productCategoryId)) {
    error.value = 'Please select a category.'
    return
  }

  loading.value = true

  try {
    await apiFetch('/product', {
      method: 'POST',
      body: {
        name: productName,
        description: productDescription,
        price: productPrice,
        stock: productStock,
        category_id: productCategoryId,
      },
    })

    toast.success('Product created')

    await navigateTo('/seller')
  }
  catch (err: any) {
    if (err?.response?.status === 422) {
      error.value = 'Please check all product details.'
    }
    else {
      error.value = 'Failed to create product.'
    }
  }
  finally {
    loading.value = false
  }
}

onMounted(() => {
  loadCategories()
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

    <PageHeader
        title="Add product"
        description="Tell shoppers what you're selling."
    />

    <form
        class="card space-y-5 p-5 sm:p-6"
        @submit.prevent="createProduct"
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
            for="name"
            class="label"
        >Name</label>
        <input
            id="name"
            v-model="name"
            type="text"
            class="input"
            required
        >
      </div>

      <div>
        <label
            for="description"
            class="label"
        >Description</label>
        <textarea
            id="description"
            v-model="description"
            class="input"
            required
        />
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label
              for="price"
              class="label"
          >Price (in paise)</label>
          <input
              id="price"
              v-model="price"
              type="number"
              min="0"
              step="1"
              inputmode="numeric"
              class="input"
              aria-describedby="price-hint"
              required
          >
          <p
              id="price-hint"
              class="hint"
          >
            {{ pricePreview ? `Shown to shoppers as ${pricePreview}` : 'Whole number, e.g. 4900 = ₹49.00' }}
          </p>
        </div>

        <div>
          <label
              for="stock"
              class="label"
          >Stock</label>
          <input
              id="stock"
              v-model="stock"
              type="number"
              min="0"
              step="1"
              inputmode="numeric"
              class="input"
              required
          >
        </div>
      </div>

      <div>
        <label
            for="category"
            class="label"
        >Category</label>
        <select
            id="category"
            v-model="categoryId"
            class="input"
            required
        >
          <option
              value=""
              disabled
          >
            {{ loadingCategories ? 'Loading categories...' : 'Select a category' }}
          </option>

          <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
      </div>

      <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <NuxtLink
            to="/seller"
            class="btn btn-ghost"
        >
          Cancel
        </NuxtLink>

        <button
            type="submit"
            class="btn btn-primary"
            :disabled="loading || loadingCategories"
        >
          {{ loading ? 'Creating...' : 'Create product' }}
        </button>
      </div>
    </form>
  </div>
</template>
