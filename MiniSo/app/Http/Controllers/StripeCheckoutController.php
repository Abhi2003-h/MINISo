<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeCheckoutController extends Controller
{
    public function createSession(Request $request)
    {
        $cart = $request->user()
            ->cart()
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $lineItems = [];

        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->name,
                        'metadata' => [
                            'product_id' => (string) $item->product->id,
                        ],
                    ],
                    'unit_amount' => $item->product->price,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $stripe = new StripeClient(
            config('services.stripe.secret')
        );

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => $lineItems,

            'metadata' => [
                'user_id' => (string) $request->user()->id,
                'cart_id' => (string) $cart->id,
            ],

            'success_url' => 'http://localhost:3000/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:3000/checkout/cancel',
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
    }
}
