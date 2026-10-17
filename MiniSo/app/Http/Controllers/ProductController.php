<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return ProductResource::collection($products);
    }
    public function sellerProducts(Request $request)
    {
        $products = Product::with('category')
            ->where('seller_id', $request->user()->id)
            ->get();

        return ProductResource::collection($products);
    }
    public function show($id){
        $product = Product::with('category')->findorFail($id);
        return new ProductResource($product);
    }
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'seller_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => new ProductResource($product->load('category')),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->seller_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not allowed to update this product.',
            ], 403);
        }

        $validated = $request->validate([
            'price' => 'required|integer|min:0',
        ]);

        $product->price = $validated['price'];

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => new ProductResource($product->load('category')),
        ]);
    }

    public function destroy($id){
        $product = Product::findorFail($id);

        if ($product->seller_id !== request()->user()->id) {
            return response()->json([
                'message' => 'You are not allowed to delete this product.',
            ], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
