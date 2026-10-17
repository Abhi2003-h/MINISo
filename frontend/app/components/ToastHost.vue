<script setup lang="ts">
const { toasts, dismiss } = useAppToast()
</script>

<template>
  <div
      aria-live="polite"
      aria-atomic="false"
      class="pointer-events-none fixed inset-x-0 bottom-4 z-50 flex justify-center px-4 sm:inset-x-auto sm:right-6 sm:bottom-6 sm:justify-end sm:px-0"
  >
    <TransitionGroup
        tag="ul"
        class="flex w-full max-w-sm flex-col gap-2"
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        leave-active-class="absolute transition duration-150 ease-in"
        leave-to-class="opacity-0"
        move-class="transition duration-200"
    >
      <li
          v-for="toast in toasts"
          :key="toast.id"
          class="pointer-events-auto flex items-center gap-2.5 rounded-lg bg-slate-900 py-2.5 pl-3.5 pr-2 text-sm text-white shadow-lg"
      >
        <AppIcon
            :name="toast.type === 'error' ? 'alert' : 'check'"
            class="size-4"
            :class="toast.type === 'error' ? 'text-red-400' : toast.type === 'info' ? 'text-slate-300' : 'text-emerald-400'"
        />
        <span class="flex-1">{{ toast.message }}</span>
        <button
            type="button"
            aria-label="Dismiss notification"
            class="inline-flex size-7 items-center justify-center rounded-md text-slate-400 transition-colors hover:bg-white/10 hover:text-white"
            @click="dismiss(toast.id)"
        >
          <AppIcon
              name="close"
              class="size-4"
          />
        </button>
      </li>
    </TransitionGroup>
  </div>
</template>
