<script setup lang="ts">
useHead({ title: 'Products' })
</script>

<template>
  <div class="container-page py-8 sm:py-12">
    <PageHeader
        title="Products"
        description="Browse everything from our independent sellers."
    />

    <ProductList />
  </div>
</template>
