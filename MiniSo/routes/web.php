<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);

Route::get('/test-product', function () {

    $product = new Product();

    $product->name = 'Mechanical Keyboard';
    $product->description = 'A mechanical keyboard for developers';
    $product->price = 4999;
    $product->stock = 20;
    $product->is_active = true;

    $product->save();

    return $product;
});

Route::get('/test-product-find', function () {

    $product = Product::find(1);

    return $product->price;
});

Route::get('/test-product', function () {

    $products = Product::all();

    return $products->first();
});

Route::get('/create-product', function () {

    $product1 = new Product();

    $product1->name = 'Wireless Mouse';
    $product1->description = 'A wireless mouse for everyday use';
    $product1->price = 2499;
    $product1->stock = 15;
    $product1->is_active = true;

    $product1->save();


    $product2 = new Product();

    $product2->name = 'USB-C Hub';
    $product2->description = 'A USB-C hub with multiple ports';
    $product2->price = 1999;
    $product2->stock = 10;
    $product2->is_active = true;

    $product2->save();


    return 'Products created!';
});

Route::get('/active-product', function () {

    $products = Product::where('price','>', 2000)->get();

    return $products;
});


Route::get('/available-product', function () {

    $products = Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->get();

    return $products;
});

Route::get('/find-product', function () {

    $product = Product::where('name', 'Wireless Mouse')->first();

    return $product->name;
});

Route::get('/update-product', function () {

    $product = Product::where('name', 'Wireless Mouse')->first();

    $product->price = 2999;

    $product->save();

    return $product;
});

Route::get('/update-myproduct', function () {

    $product = Product::where('name', 'USB-C Hub')->first();
    $product->stock = 25;
    $product->save();
    return $product;
});

Route::get('/delete-product', function () {

    $product = Product::where('name', 'USB-C Hub')->first();

    $product->delete();

    return 'Product deleted';
});
