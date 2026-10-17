export default defineNuxtRouteMiddleware(async () => {
    // Same reason as auth.ts: no localStorage token during SSR.
    if (import.meta.server) {
        return
    }

    const { isLoggedIn } = useAuth()

    // On a hard refresh the shared state is still empty until after hydration,
    // so also accept the token stored in localStorage.
    if (!isLoggedIn.value && !localStorage.getItem('token')) {
        return navigateTo('/login')
    }

    try {
        const response = await apiFetch<{
            user: {
                id: number
                name: string
                email: string
                role: string
            }
        }>('/auth/user')

        if (response.user.role !== 'seller') {
            return navigateTo('/')
        }
    }
    catch {
        return navigateTo('/login')
    }
})