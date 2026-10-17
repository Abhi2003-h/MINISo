import type { Product } from '~/types/product'

/**
 * Add-to-cart behaviour shared by the listing, home and product pages.
 * Same request as before: POST /cart/items { product_id, quantity }.
 */
export function useAddToCart() {
    const { isLoggedIn } = useAuth()
    const route = useRoute()
    const toast = useAppToast()
    const cart = useCartCount()

    const addingId = ref<number | null>(null)

    async function addToCart(product: Product, quantity = 1): Promise<boolean> {
        if (!isLoggedIn.value) {
            await navigateTo({
                path: '/login',
                query: {
                    redirect: route.fullPath,
                },
            })
            return false
        }

        addingId.value = product.id

        try {
            await apiFetch('/cart/items', {
                method: 'POST',
                body: {
                    product_id: product.id,
                    quantity,
                },
            })

            toast.success(`${product.name} added to cart`)
            cart.refresh()

            return true
        }
        catch {
            toast.error('Failed to add product to cart.')

            return false
        }
        finally {
            addingId.value = null
        }
    }

    return { addToCart, addingId }
}
