import type { Product } from '~/types/product'

interface ProductsResponse {
    data: Product[]
}

/**
 * Loads the product list from the existing `/product` endpoint.
 * The result is cached under one key, so the header, home page and
 * product listing all share a single request.
 */
export function useProducts() {
    const { data, pending, error, refresh } = useAsyncData(
        'products',
        () => apiFetch<ProductsResponse>('/product'),
    )

    const products = computed<Product[]>(() => data.value?.data ?? [])

    const categories = computed(() => {
        const unique = new Map<number, string>()

        for (const product of products.value) {
            if (product.category) {
                unique.set(product.category.id, product.category.name)
            }
        }

        return Array.from(unique, ([id, name]) => ({ id, name }))
    })

    return { products, categories, pending, error, refresh }
}
