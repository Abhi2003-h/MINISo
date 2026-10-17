<script setup lang="ts">
import type { Product } from '~/types/product'

const { products, categories, pending, error, refresh } = useProducts()
const { addToCart, addingId } = useAddToCart()
const { isLoggedIn: authed } = useAuth()

const mounted = ref(false)
const isLoggedIn = computed(() => mounted.value && authed.value)

onMounted(() => {
  mounted.value = true
})

const featured = computed(() => products.value.slice(0, 8))

function handleAddToCart(product: Product) {
  addToCart(product)
}

const values = [
  {
    icon: 'heart',
    title: 'Curated products',
    text: 'A focused catalogue of things worth owning, without the noise.',
  },
  {
    icon: 'bag',
    title: 'Independent sellers',
    text: 'Every product comes from a seller running their own small shop.',
  },
  {
    icon: 'shield',
    title: 'Secure checkout',
    text: 'Pay safely with Stripe. Your cart is saved until you are ready.',
  },
] as const
</script>

<template>
  <div>
    <!-- Hero -->
    <section class="overflow-hidden border-b border-slate-100 bg-gradient-to-b from-white to-surface">
      <div class="container-page grid items-center gap-12 py-12 sm:py-16 lg:grid-cols-2 lg:gap-8 lg:py-24">
        <div class="max-w-xl">
          <p class="inline-flex items-center gap-2 rounded-full border border-brand-100 bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700">
            <span class="size-1.5 rounded-full bg-brand-600" />
            Discover things you'll love
          </p>

          <h1 class="mt-5 text-4xl font-bold leading-[1.08] sm:text-5xl lg:text-6xl">
            Small shop.<br>
            <span class="text-brand-600">Big finds.</span>
          </h1>

          <p class="mt-5 max-w-md text-base leading-7 text-slate-600 sm:text-lg">
            Discover products from independent sellers. Simple shopping,
            curated products, trusted sellers.
          </p>

          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <NuxtLink
                to="/products"
                class="btn btn-primary btn-lg"
            >
              Explore products
              <AppIcon
                  name="arrow-right"
                  class="size-4"
              />
            </NuxtLink>
            <NuxtLink
                v-if="!isLoggedIn"
                to="/register"
                class="btn btn-secondary btn-lg"
            >
              Create account
            </NuxtLink>
          </div>
        </div>

        <HeroPicks :products="products" />
      </div>
    </section>

    <!-- Categories -->
    <section
        v-if="categories.length"
        class="container-page pt-12 sm:pt-16"
        aria-labelledby="categories-heading"
    >
      <h2
          id="categories-heading"
          class="text-xl sm:text-2xl"
      >
        Shop by category
      </h2>
      <div class="mt-4 flex flex-wrap gap-2">
        <NuxtLink
            v-for="category in categories"
            :key="category.id"
            :to="{ path: '/products', query: { category: category.id } }"
            class="inline-flex h-10 items-center rounded-full border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition-colors hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700"
        >
          {{ category.name }}
        </NuxtLink>
      </div>
    </section>

    <!-- Featured products -->
    <section
        class="container-page pt-12 sm:pt-16"
        aria-labelledby="featured-heading"
    >
      <div class="mb-6 flex items-end justify-between gap-4">
        <h2
            id="featured-heading"
            class="text-xl sm:text-2xl"
        >
          Fresh picks
        </h2>
        <NuxtLink
            to="/products"
            class="inline-flex items-center gap-1 text-sm font-medium text-brand-600 transition-colors hover:text-brand-700"
        >
          View all
          <AppIcon
              name="arrow-right"
              class="size-4"
          />
        </NuxtLink>
      </div>

      <LoadingSkeleton
          v-if="pending && products.length === 0"
          variant="products"
          :count="4"
      />
      <ErrorState
          v-else-if="error"
          message="We couldn't load the products."
          :loading="pending"
          @retry="refresh()"
      />
      <EmptyState
          v-else-if="featured.length === 0"
          icon="package"
          title="No products yet"
          description="Check back soon. New products from our sellers are on the way."
      />
      <ProductGrid
          v-else
          :products="featured"
          :adding-id="addingId"
          @add-to-cart="handleAddToCart"
      />
    </section>

    <!-- Values -->
    <section class="container-page pt-16 sm:pt-24">
      <ul class="grid gap-8 sm:grid-cols-3">
        <li
            v-for="value in values"
            :key="value.title"
            class="flex gap-4 sm:block"
        >
          <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
            <AppIcon :name="value.icon" />
          </span>
          <div>
            <h3 class="text-base sm:mt-4">
              {{ value.title }}
            </h3>
            <p class="mt-1.5 text-sm leading-6 text-slate-500">
              {{ value.text }}
            </p>
          </div>
        </li>
      </ul>
    </section>

    <!-- Seller CTA -->
    <section class="container-page pt-16 sm:pt-24">
      <div class="relative overflow-hidden rounded-xl border border-brand-100 bg-brand-50 px-6 py-10 sm:px-12 sm:py-14">
        <div
            class="dot-grid absolute -right-2 -top-2 size-28 opacity-70"
            aria-hidden="true"
        />
        <div class="relative max-w-lg">
          <h2 class="text-2xl text-brand-950 sm:text-3xl">
            Got something to sell?
          </h2>
          <p class="mt-2 text-slate-600">
            Open your small shop on MiniShop and reach people who love
            discovering independent finds.
          </p>
          <NuxtLink
              :to="isLoggedIn ? '/seller' : '/register'"
              class="btn btn-primary mt-6"
          >
            {{ isLoggedIn ? 'Go to seller dashboard' : 'Start selling' }}
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>
