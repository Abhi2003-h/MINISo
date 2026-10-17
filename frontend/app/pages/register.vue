<script setup lang="ts">
import { ref } from 'vue'
import { apiFetch } from '~/utils/api'

interface User {
  id: number
  name: string
  email: string
}

interface RegisterResponse {
  user: User
  token: string
}

const name = ref('')
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

useHead({ title: 'Create account' })

const route = useRoute()
const { login: setLogin } = useAuth()
const toast = useAppToast()

const redirectQuery = computed(() =>
  typeof route.query.redirect === 'string'
    ? { redirect: route.query.redirect }
    : {},
)

async function register() {
  error.value = ''

  const userName = name.value.trim()
  const userEmail = email.value.trim()
  const userPassword = password.value

  if (!userName) {
    error.value = 'Name is required.'
    return
  }

  if (!userEmail) {
    error.value = 'Email is required.'
    return
  }

  if (!userEmail.includes('@')) {
    error.value = 'Please enter a valid email address.'
    return
  }

  if (userPassword.length < 8) {
    error.value = 'Password must be at least 8 characters.'
    return
  }

  loading.value = true

  try {
    const response = await apiFetch<RegisterResponse>('/auth/register', {
      method: 'POST',
      body: {
        name: userName,
        email: userEmail,
        password: userPassword,
      },
    })

    setLogin(response.token)

    toast.success('Account created. Welcome to MiniShop!')

    const redirect = typeof route.query.redirect === 'string'
        ? route.query.redirect
        : '/'

    await navigateTo(redirect)
  }
  catch (err: any) {
    if (err?.response?.status === 422) {
      error.value = 'This email may already be registered or the details are invalid.'
    }
    else {
      error.value = 'Registration failed. Please try again.'
    }
  }
  finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="container-page flex justify-center py-12 sm:py-20">
    <div class="w-full max-w-sm">
      <div class="text-center">
        <h1 class="text-2xl sm:text-3xl">
          Create an account
        </h1>
        <p class="mt-2 text-sm text-slate-500">
          Start shopping small, find big.
        </p>
      </div>

      <form
          class="card mt-8 space-y-5 p-6"
          @submit.prevent="register"
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
              for="name"
              class="label"
          >Name</label>
          <input
              id="name"
              v-model="name"
              type="text"
              class="input"
              placeholder="Your name"
              autocomplete="name"
              required
          >
        </div>

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
              placeholder="At least 8 characters"
              autocomplete="new-password"
              minlength="8"
              required
          >
        </div>

        <button
            type="submit"
            class="btn btn-primary w-full"
            :disabled="loading"
        >
          {{ loading ? 'Creating account...' : 'Create account' }}
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-slate-500">
        Already have an account?
        <NuxtLink
            :to="{ path: '/login', query: redirectQuery }"
            class="font-medium text-brand-600 hover:text-brand-700"
        >
          Log in
        </NuxtLink>
      </p>
    </div>
  </div>
</template>
