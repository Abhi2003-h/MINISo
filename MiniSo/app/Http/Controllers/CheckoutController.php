<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = $request->user()->cart;

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty.',
            ], 422);
        }

        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty.',
            ], 422);
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return response()->json([
                    'message' => 'Not enough stock available for ' . $item->product->name,
                ], 422);
            }
        }

        $total = 0;

        foreach ($cart->items as $item) {
            $total += $item->product->price * $item->quantity;
        }

        $order = DB::transaction(function () use ($request, $cart, $total) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total' => $total,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'seller_id' => $item->product->seller_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->stock -= $item->quantity;
                $item->product->save();
            }
            $cart->items()->delete();

            return $order;
        });

        return response()->json([
            'message' => 'Order created successfully.',
            'order' => $order,
        ], 201);
    }
}
