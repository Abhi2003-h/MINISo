<script setup lang="ts">
withDefaults(defineProps<{
  title?: string
  message?: string
  loading?: boolean
}>(), {
  title: 'Something went wrong.',
  message: 'Please try again in a moment.',
})

const emit = defineEmits<{
  retry: []
}>()
</script>

<template>
  <div
      role="alert"
      class="mx-auto flex max-w-sm flex-col items-center px-4 py-16 text-center"
  >
    <span class="inline-flex size-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
      <AppIcon
          name="alert"
          class="size-6"
      />
    </span>

    <h2 class="mt-5 text-lg">
      {{ title }}
    </h2>
    <p class="mt-2 text-sm text-slate-500">
      {{ message }}
    </p>

    <button
        type="button"
        class="btn btn-secondary mt-6"
        :disabled="loading"
        @click="emit('retry')"
    >
      {{ loading ? 'Trying again...' : 'Try again' }}
    </button>
  </div>
</template>
