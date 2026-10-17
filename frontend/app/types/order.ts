import type { Product } from './product'

export interface OrderItem {
    id: number
    product_id: number
    quantity: number
    price: number
    product: Product
}

export interface Order {
    id: number
    user_id: number
    status: string
    total: number
    items: OrderItem[]
    created_at: string
}

export interface OrdersResponse {
    orders: Order[]
}