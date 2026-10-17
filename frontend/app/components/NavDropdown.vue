<script setup lang="ts">
withDefaults(defineProps<{
  align?: 'left' | 'right'
}>(), {
  align: 'left',
})

const open = ref(false)
const root = ref<HTMLElement | null>(null)
const route = useRoute()

function toggle() {
  open.value = !open.value
}

function close() {
  open.value = false
}

function onDocumentClick(event: MouseEvent) {
  if (root.value && !root.value.contains(event.target as Node)) {
    close()
  }
}

function onKeydown(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    close()
  }
}

onMounted(() => {
  document.addEventListener('click', onDocumentClick)
  document.addEventListener('keydown', onKeydown)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', onDocumentClick)
  document.removeEventListener('keydown', onKeydown)
})

watch(() => route.fullPath, close)
</script>

<template>
  <div
      ref="root"
      class="relative"
  >
    <slot
        name="trigger"
        :open="open"
        :toggle="toggle"
    />

    <Transition
        enter-active-class="transition duration-150 ease-out"
        enter-from-class="-translate-y-1 opacity-0"
        leave-active-class="transition duration-100 ease-in"
        leave-to-class="-translate-y-1 opacity-0"
    >
      <div
          v-if="open"
          class="absolute top-full z-50 mt-2 min-w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg"
          :class="align === 'right' ? 'right-0' : 'left-0'"
          @click="close"
      >
        <slot :close="close" />
      </div>
    </Transition>
  </div>
</template>
