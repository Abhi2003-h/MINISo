<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
class OrderService
{
    public function createFromStripe(
        int $userId,
        int $cartId,
        string $stripeSessionId,
        $session,
        $lineItems,
        StripeClient $stripe
    ) {
        return DB::transaction(function () use (
            $userId,
            $cartId,
            $stripeSessionId,
            $session,
            $lineItems,
            $stripe
        ) {
            $existingOrder = Order::where(
                'stripe_session_id',
                $stripeSessionId
            )->first();

            if ($existingOrder) {
                return $existingOrder;
            }

            $cart = Cart::with('items')
                ->find($cartId);

            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception(
                    'Cart not found or already empty.'
                );
            }

            $products = [];

            foreach ($cart->items as $item) {
                $product = Product::where(
                    'id',
                    $item->product_id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception(
                        'Product no longer exists.'
                    );
                }

                if ($product->stock < $item->quantity) {
                    throw new \Exception(
                        "Not enough stock for {$product->name}."
                    );
                }

                $products[$product->id] = $product;
            }
            $stripeProducts = [];

            foreach ($lineItems->data as $lineItem) {
                $stripeProductId = $lineItem->price->product;

                $stripeProduct = $stripe->products->retrieve(
                    $stripeProductId,
                    []
                );

                $productId = $stripeProduct->metadata->product_id ?? null;

                if (!$productId) {
                    throw new \Exception(
                        'Stripe product is missing local product ID.'
                    );
                }

                $stripeProducts[(int) $productId] = [
                    'unit_amount' => $lineItem->price->unit_amount,
                    'quantity' => $lineItem->quantity,
                ];
            }
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'paid',
                'total' => $session->amount_total,
                'stripe_session_id' => $stripeSessionId,
            ]);

            foreach ($cart->items as $item) {
                $product = $products[$item->product_id];

                $stripeProduct = $stripeProducts[$product->id] ?? null;

                if (!$stripeProduct) {
                    throw new \Exception(
                        'Stripe product does not match cart product.'
                    );
                }

                if ($stripeProduct['quantity'] !== $item->quantity) {
                    throw new \Exception(
                        'Stripe quantity does not match cart quantity.'
                    );
                }

                $order->items()->create([
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id,
                    'quantity' => $item->quantity,
                    'price' => $stripeProduct['unit_amount'],
                ]);

                $product->decrement('stock', $item->quantity);
            }
            $cart->items()->delete();

            return $order;
        });
    }
}
