export function useAuth() {
    const token = useState<string | null>('auth-token', () => {
        if (import.meta.client) {
            return localStorage.getItem('token')
        }

        return null
    })

    const isLoggedIn = computed(() => {
        return !!token.value
    })

    function login(newToken: string) {
        localStorage.setItem('token', newToken)
        token.value = newToken
    }

    async function logout() {
        try {
            if (token.value) {
                await apiFetch('/auth/logout', {
                    method: 'POST',
                })
            }
        }
        catch (error) {
            console.error('Logout API error:', error)
        }
        finally {
            localStorage.removeItem('token')
            token.value = null

            await navigateTo('/login')
        }
    }

    return {
        token,
        isLoggedIn,
        login,
        logout,
    }
}