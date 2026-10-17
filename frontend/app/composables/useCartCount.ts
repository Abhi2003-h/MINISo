import type { CartResponse } from '~/types/cart'

/**
 * Shared cart item count used by the header badge.
 * It only reads from the existing `/cart` endpoint.
 */
export function useCartCount() {
    const count = useState<number>('cart-count', () => 0)
    const { isLoggedIn } = useAuth()

    function setFromItems(items: { quantity: number }[]) {
        count.value = items.reduce((sum, item) => sum + item.quantity, 0)
    }

    async function refresh() {
        if (!isLoggedIn.value) {
            count.value = 0
            return
        }

        try {
            const response = await apiFetch<CartResponse>('/cart')
            setFromItems(response.cart.items)
        }
        catch {
            // The badge is a nicety; never surface errors for it.
        }
    }

    return { count, refresh, setFromItems }
}
