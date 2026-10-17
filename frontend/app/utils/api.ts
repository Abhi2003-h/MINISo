export async function apiFetch<T>(
    url: string,
    options: Parameters<typeof $fetch>[1] = {}
): Promise<T> {
    const token = import.meta.client
        ? localStorage.getItem('token')
        : null

    const headers = new Headers(options.headers)

    if (token) {
        headers.set('Authorization', `Bearer ${token}`)
    }

    try {
        return await $fetch<T>(
            `http://127.0.0.1:8000/api${url}`,
            {
                ...options,
                headers,
            }
        )
    } catch (error: any) {
        console.error('API Error:', error)

        throw error
    }
}