<script setup lang="ts">
const { isLoggedIn: authed, logout } = useAuth()
const { categories } = useProducts()
const { count, refresh: refreshCart } = useCartCount()
const toast = useAppToast()
const route = useRoute()

// Auth state comes from localStorage, which the server can't see. Only trust it
// after mount so the first client render matches the server-rendered HTML.
const mounted = ref(false)
const isLoggedIn = computed(() => mounted.value && authed.value)

const mobileOpen = ref(false)
const navSearch = ref('')

const onProducts = computed(() => route.path === '/products')
const isProductsActive = computed(
  () => route.path.startsWith('/product') && !route.query.category,
)
const isCategoryActive = computed(
  () => route.path === '/products' && !!route.query.category,
)

function linkClass(active: boolean) {
  return [
    'inline-flex h-9 items-center gap-1.5 rounded-lg px-3 text-sm font-medium transition-colors',
    active
      ? 'bg-brand-50 text-brand-700'
      : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
  ]
}

async function submitSearch(value: string) {
  mobileOpen.value = false

  await navigateTo({
    path: '/products',
    query: value ? { q: value } : {},
  })
}

async function handleLogout() {
  mobileOpen.value = false
  await logout()
  toast.info('You have been logged out')
}

// Cart badge: load on mount, then follow login / logout.
onMounted(() => {
  mounted.value = true
})
watch(isLoggedIn, (loggedIn) => {
  if (loggedIn) {
    refreshCart()
  }
  else {
    count.value = 0
  }
})

watch(() => route.fullPath, () => {
  mobileOpen.value = false
})
</script>

<template>
  <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="container-page flex h-16 items-center gap-4 lg:gap-8">
      <NuxtLink
          to="/"
          aria-label="MiniShop home"
          class="rounded-lg"
      >
        <BrandLogo />
      </NuxtLink>

      <!-- Desktop navigation -->
      <nav
          aria-label="Primary"
          class="hidden items-center gap-1 lg:flex"
      >
        <NuxtLink
            to="/products"
            :class="linkClass(isProductsActive)"
        >
          Products
        </NuxtLink>

        <NavDropdown>
          <template #trigger="{ open, toggle }">
            <button
                type="button"
                aria-haspopup="true"
                :aria-expanded="open"
                :class="linkClass(isCategoryActive || open)"
                @click="toggle"
            >
              Categories
              <AppIcon
                  name="chevron-down"
                  class="size-4 transition-transform duration-150"
                  :class="open && 'rotate-180'"
              />
            </button>
          </template>

          <NuxtLink
              to="/products"
              class="menu-item"
          >
            All products
          </NuxtLink>
          <NuxtLink
              v-for="category in categories"
              :key="category.id"
              :to="{ path: '/products', query: { category: category.id } }"
              class="menu-item"
          >
            {{ category.name }}
          </NuxtLink>
        </NavDropdown>

        <NuxtLink
            v-if="isLoggedIn"
            to="/orders"
            :class="linkClass(route.path === '/orders')"
        >
          Orders
        </NuxtLink>
      </nav>

      <div class="ml-auto flex items-center gap-1.5 sm:gap-2">
        <SearchBar
            v-if="!onProducts"
            v-model="navSearch"
            class="hidden w-64 lg:block"
            @submit="submitSearch"
        />

        <NuxtLink
            to="/cart"
            class="btn btn-ghost relative !px-2.5"
            :aria-label="count > 0 ? `Cart, ${count} items` : 'Cart'"
        >
          <AppIcon name="cart" />
          <span
              v-if="count > 0"
              :key="count"
              class="absolute right-0.5 top-0.5 inline-flex min-w-4.5 animate-pop items-center justify-center rounded-full bg-brand-600 px-1 text-[11px] font-semibold leading-[18px] text-white"
          >
            {{ count > 99 ? '99+' : count }}
          </span>
        </NuxtLink>

        <!-- Desktop account -->
        <div class="hidden lg:block">
          <NavDropdown
              v-if="isLoggedIn"
              key="account-menu"
              align="right"
          >
            <template #trigger="{ open, toggle }">
              <button
                  type="button"
                  aria-haspopup="true"
                  :aria-expanded="open"
                  :class="linkClass(open)"
                  @click="toggle"
              >
                <AppIcon name="user" />
                Account
                <AppIcon
                    name="chevron-down"
                    class="size-4 transition-transform duration-150"
                    :class="open && 'rotate-180'"
                />
              </button>
            </template>

            <NuxtLink
                to="/orders"
                class="menu-item"
            >
              <AppIcon
                  name="package"
                  class="size-4"
              />
              My orders
            </NuxtLink>
            <NuxtLink
                to="/seller"
                class="menu-item"
            >
              <AppIcon
                  name="dashboard"
                  class="size-4"
              />
              Seller dashboard
            </NuxtLink>
            <button
                type="button"
                class="menu-item"
                @click="handleLogout"
            >
              <AppIcon
                  name="logout"
                  class="size-4"
              />
              Log out
            </button>
          </NavDropdown>

          <div
              v-else
              key="auth-links"
              class="flex items-center gap-2"
          >
            <NuxtLink
                to="/login"
                class="btn btn-ghost btn-sm"
            >
              Log in
            </NuxtLink>
            <NuxtLink
                to="/register"
                class="btn btn-primary btn-sm"
            >
              Sign up
            </NuxtLink>
          </div>
        </div>

        <!-- Mobile menu toggle -->
        <button
            type="button"
            class="btn btn-ghost !px-2.5 lg:hidden"
            :aria-expanded="mobileOpen"
            aria-controls="mobile-menu"
            :aria-label="mobileOpen ? 'Close menu' : 'Open menu'"
            @click="mobileOpen = !mobileOpen"
        >
          <AppIcon :name="mobileOpen ? 'close' : 'menu'" />
        </button>
      </div>
    </div>

    <!-- Mobile navigation -->
    <Transition
        enter-active-class="transition duration-150 ease-out"
        enter-from-class="-translate-y-2 opacity-0"
        leave-active-class="transition duration-100 ease-in"
        leave-to-class="-translate-y-2 opacity-0"
    >
      <div
          v-if="mobileOpen"
          id="mobile-menu"
          class="border-t border-slate-200 bg-white lg:hidden"
      >
        <nav
            aria-label="Mobile"
            class="container-page max-h-[calc(100dvh-4rem)] space-y-5 overflow-y-auto py-4"
        >
          <SearchBar
              v-if="!onProducts"
              v-model="navSearch"
              @submit="submitSearch"
          />

          <div class="space-y-0.5">
            <NuxtLink
                to="/products"
                class="menu-item !min-h-11"
            >
              All products
            </NuxtLink>
            <NuxtLink
                v-if="isLoggedIn"
                to="/orders"
                class="menu-item !min-h-11"
            >
              My orders
            </NuxtLink>
            <NuxtLink
                v-if="isLoggedIn"
                to="/seller"
                class="menu-item !min-h-11"
            >
              Seller dashboard
            </NuxtLink>
          </div>

          <div v-if="categories.length">
            <p class="mb-2 px-3 text-xs font-medium uppercase tracking-wide text-slate-400">
              Categories
            </p>
            <div class="flex flex-wrap gap-2 px-3">
              <NuxtLink
                  v-for="category in categories"
                  :key="category.id"
                  :to="{ path: '/products', query: { category: category.id } }"
                  class="inline-flex h-9 items-center rounded-full border border-slate-200 px-3.5 text-sm text-slate-600 transition-colors hover:border-brand-300 hover:text-brand-700"
              >
                {{ category.name }}
              </NuxtLink>
            </div>
          </div>

          <div class="border-t border-slate-100 pt-4">
            <button
                v-if="isLoggedIn"
                type="button"
                class="btn btn-secondary w-full"
                @click="handleLogout"
            >
              Log out
            </button>
            <div
                v-else
                class="grid grid-cols-2 gap-3"
            >
              <NuxtLink
                  to="/login"
                  class="btn btn-secondary"
              >
                Log in
              </NuxtLink>
              <NuxtLink
                  to="/register"
                  class="btn btn-primary"
              >
                Sign up
              </NuxtLink>
            </div>
          </div>
        </nav>
      </div>
    </Transition>
  </header>
</template>
