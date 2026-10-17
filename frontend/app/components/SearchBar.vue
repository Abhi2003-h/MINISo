<script setup lang="ts">
const model = defineModel<string>({ default: '' })

withDefaults(defineProps<{
  placeholder?: string
  label?: string
}>(), {
  placeholder: 'Search products...',
  label: 'Search products',
})

const emit = defineEmits<{
  submit: [value: string]
}>()

const id = useId()
</script>

<template>
  <form
      role="search"
      class="relative"
      @submit.prevent="emit('submit', model.trim())"
  >
    <label
        :for="id"
        class="sr-only"
    >
      {{ label }}
    </label>

    <AppIcon
        name="search"
        class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400"
    />

    <input
        :id="id"
        v-model="model"
        type="search"
        class="input pl-9 pr-9"
        :placeholder="placeholder"
        autocomplete="off"
    >

    <button
        v-if="model"
        type="button"
        aria-label="Clear search"
        class="absolute right-1.5 top-1/2 inline-flex size-8 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700"
        @click="model = ''; emit('submit', '')"
    >
      <AppIcon
          name="close"
          class="size-4"
      />
    </button>
  </form>
</template>
