<script setup lang="ts">
import type { Product } from '~/types/product'

const route = useRoute()
const router = useRouter()

const { products, categories, pending, error, refresh } = useProducts()
const { addToCart, addingId } = useAddToCart()

// Search + category state, mirrored in the URL (?q=...&category=...)
const search = ref(typeof route.query.q === 'string' ? route.query.q : '')
const selectedCategory = ref(
  typeof route.query.category === 'string' ? route.query.category : 'all',
)

watch(() => route.query.q, (q) => {
  const next = typeof q === 'string' ? q : ''
  if (next !== search.value) {
    search.value = next
  }
})

watch(() => route.query.category, (category) => {
  const next = typeof category === 'string' ? category : 'all'
  if (next !== selectedCategory.value) {
    selectedCategory.value = next
  }
})

watch([search, selectedCategory], ([q, category]) => {
  const query = { ...route.query }

  if (q) query.q = q
  else delete query.q

  if (category !== 'all') query.category = category
  else delete query.category

  router.replace({ query })
})

const filteredProducts = computed(() => {
  const searchTerm = search.value.toLowerCase().trim()

  return products.value.filter((product) => {
    const matchesSearch =
        product.name.toLowerCase().includes(searchTerm)

    const matchesCategory =
        selectedCategory.value === 'all' ||
        product.category?.id === Number(selectedCategory.value)

    return matchesSearch && matchesCategory
  })
})

const isFiltering = computed(
  () => search.value.trim() !== '' || selectedCategory.value !== 'all',
)

function clearFilters() {
  search.value = ''
  selectedCategory.value = 'all'
}

function handleAddToCart(product: Product) {
  addToCart(product)
}
</script>

<template>
  <div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <SearchBar
          v-model="search"
          class="w-full sm:max-w-md"
      />

      <p
          v-if="!pending && !error"
          class="text-sm text-slate-500"
          aria-live="polite"
      >
        {{ filteredProducts.length }}
        {{ filteredProducts.length === 1 ? 'product' : 'products' }}
      </p>
    </div>

    <CategoryFilter
        v-if="categories.length"
        v-model="selectedCategory"
        :categories="categories"
        class="mt-4"
    />

    <div class="mt-6 sm:mt-8">
      <LoadingSkeleton
          v-if="pending && products.length === 0"
          variant="products"
      />

      <ErrorState
          v-else-if="error"
          message="We couldn't load the products."
          :loading="pending"
          @retry="refresh()"
      />

      <EmptyState
          v-else-if="products.length === 0"
          icon="package"
          title="No products yet"
          description="Check back soon. New products from our sellers are on the way."
      />

      <EmptyState
          v-else-if="filteredProducts.length === 0"
          icon="search"
          title="No products found"
          description="Try a different search or browse all categories."
      >
        <button
            v-if="isFiltering"
            type="button"
            class="btn btn-secondary"
            @click="clearFilters"
        >
          Clear filters
        </button>
      </EmptyState>

      <ProductGrid
          v-else
          :products="filteredProducts"
          :adding-id="addingId"
          @add-to-cart="handleAddToCart"
      />
    </div>
  </div>
</template>
