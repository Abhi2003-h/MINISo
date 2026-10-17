export default defineNuxtRouteMiddleware(() => {
    // The token lives in localStorage, which doesn't exist during SSR.
    // Only check on the client, otherwise a page refresh redirects to /login.
    if (import.meta.server) {
        return
    }

    const { isLoggedIn } = useAuth()

    // On a hard refresh the shared state is still empty until after hydration,
    // so also accept the token stored in localStorage.
    if (!isLoggedIn.value && !localStorage.getItem('token')) {
        return navigateTo('/login')
    }
})