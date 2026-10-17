/**
 * The auth token lives in localStorage, but server-rendered state starts as
 * "logged out". After hydration, sync the UI state with the stored token so
 * the header shows the right account state. No auth behaviour changes.
 */
export default defineNuxtPlugin((nuxtApp) => {
    nuxtApp.hook('app:mounted', () => {
        const { token } = useAuth()

        if (!token.value) {
            const stored = localStorage.getItem('token')

            if (stored) {
                token.value = stored
            }
        }
    })
})
