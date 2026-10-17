export type ToastType = 'success' | 'error' | 'info'

export interface ToastItem {
    id: number
    message: string
    type: ToastType
}

let nextId = 1

export function useAppToast() {
    const toasts = useState<ToastItem[]>('toasts', () => [])

    function dismiss(id: number) {
        toasts.value = toasts.value.filter(toast => toast.id !== id)
    }

    function push(message: string, type: ToastType = 'success', duration = 3200) {
        const id = nextId++

        // Keep the stack small and unobtrusive.
        toasts.value = [...toasts.value.slice(-2), { id, message, type }]

        if (import.meta.client) {
            window.setTimeout(() => dismiss(id), duration)
        }
    }

    return {
        toasts,
        dismiss,
        push,
        success: (message: string) => push(message, 'success'),
        error: (message: string) => push(message, 'error', 4500),
        info: (message: string) => push(message, 'info'),
    }
}
