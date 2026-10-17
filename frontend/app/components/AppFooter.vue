<template>
  <footer class="mt-24 border-t border-slate-200 bg-surface">
    <div class="container-page flex flex-col gap-6 py-10 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <BrandLogo />
        <p class="mt-3 max-w-xs text-sm text-slate-500">
          Small shop. Big finds. Products from independent sellers.
        </p>
      </div>

      <nav
          aria-label="Footer"
          class="flex flex-wrap gap-x-6 gap-y-2 text-sm"
      >
        <NuxtLink
            to="/products"
            class="text-slate-600 transition-colors hover:text-brand-700"
        >
          Products
        </NuxtLink>
        <NuxtLink
            to="/orders"
            class="text-slate-600 transition-colors hover:text-brand-700"
        >
          Orders
        </NuxtLink>
        <NuxtLink
            to="/seller"
            class="text-slate-600 transition-colors hover:text-brand-700"
        >
          Sell on MiniShop
        </NuxtLink>
      </nav>
    </div>
  </footer>
</template>
