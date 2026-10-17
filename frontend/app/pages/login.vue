<script setup lang="ts">
import { apiFetch } from '~/utils/api'
interface User {
  id: number
  name: string
  email: string
}

interface LoginResponse {
  user: User
  token: string
}

useHead({ title: 'Log in' })

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const { login: setLogin } = useAuth()
const toast = useAppToast()

const route = useRoute()

const redirectQuery = computed(() =>
  typeof route.query.redirect === 'string'
    ? { redirect: route.query.redirect }
    : {},
)

async function login() {
  error.value = ''
  loading.value = true

  try {
    const response = await apiFetch<LoginResponse>('/auth/login', {
      method: 'POST',
      body: {
        email: email.value,
        password: password.value,
      },
    })

    setLogin(response.token)

    toast.success(`Welcome back, ${response.user.name}`)

    const redirect = typeof route.query.redirect === 'string'
        ? route.query.redirect
        : '/'

    await navigateTo(redirect)
  } catch (err) {
    error.value = 'Invalid email or password.'
  } finally {
    loading.value = false
  }
}

</script>

<template>
  <div class="container-page flex justify-center py-12 sm:py-20">
    <div class="w-full max-w-sm">
      <div class="text-center">
        <h1 class="text-2xl sm:text-3xl">
          Welcome back
        </h1>
        <p class="mt-2 text-sm text-slate-500">
          Log in to your MiniShop account.
        </p>
      </div>

      <form
          class="card mt-8 space-y-5 p-6"
          @submit.prevent="login"
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
              for="email"
              class="label"
          >Email</label>
          <input
              id="email"
              v-model="email"
              type="email"
              class="input"
              placeholder="you@example.com"
              autocomplete="email"
              required
          >
        </div>

        <div>
          <label
              for="password"
              class="label"
          >Password</label>
          <input
              id="password"
              v-model="password"
              type="password"
              class="input"
              autocomplete="current-password"
              required
          >
        </div>

        <button
            type="submit"
            class="btn btn-primary w-full"
            :disabled="loading"
        >
          {{ loading ? 'Logging in...' : 'Log in' }}
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-slate-500">
        New to MiniShop?
        <NuxtLink
            :to="{ path: '/register', query: redirectQuery }"
            class="font-medium text-brand-600 hover:text-brand-700"
        >
          Create an account
        </NuxtLink>
      </p>
    </div>
  </div>
</template>
