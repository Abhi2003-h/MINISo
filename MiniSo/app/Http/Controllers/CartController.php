<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        //stock validation
        if ($validated['quantity'] > $product->stock) {
            return response()->json([
                'message' => 'Not enough stock available.',
            ], 422);
        }

        $cart = $request->user()->cart;

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $request->user()->id,
            ]);
        }

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $newQuantity = $item->quantity + $validated['quantity'];

            if ($newQuantity > $product->stock) {
                return response()->json([
                    'message' => 'Not enough stock available.',
                ], 422);
            }

            $item->quantity = $newQuantity;
            $item->save();

        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'item' => $item,
        ], 201);
    }

    public function index(Request $request)
    {
        $cart = $request->user()->cart;

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty.',
            ]);
        }

        $cart->load('items.product');

        $total = 0;

        foreach ($cart->items as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return response()->json([
            'cart'=>$cart,
            'total'=>$total,
        ]);
    }

    public function removeItem(Request $request, $id)
    {
        $cart = $request->user()->cart;

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty.',
            ]);
        }

        $item = $cart->items()->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item removed from cart successfully.']);
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->user()->cart;

        if (!$cart) {
            return response()->json([
                'message' => 'Cart is empty.',
            ]);
        }

        $item = $cart->items()->findOrFail($id);
        if($request->quantity > $item->product->stock) {
            return response()->json([
                'message' => 'Not enough stock available.',
            ],422);
        }
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['message' => 'Item updated successfully.']);

    }


}
