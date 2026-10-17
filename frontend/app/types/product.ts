export interface Category {
    id: number
    name: string
}

export interface Product {
    id: number
    name: string
    description: string
    price: number
    stock: number
    category_id: number
    seller_id: number
    category?: Category | null
}