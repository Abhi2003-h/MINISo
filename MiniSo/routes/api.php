<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\StripeWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/product', [ProductController::class, 'index']);

Route::get('/product/{id}', [ProductController::class, 'show']);

Route::middleware(['auth:sanctum', 'seller'])->post(
    '/product',
    [ProductController::class, 'store']
);

Route::middleware(['auth:sanctum', 'seller'])->put(
    '/product/{id}',
    [ProductController::class, 'update']
);

Route::middleware(['auth:sanctum', 'seller'])->delete(
    '/product/{id}',
    [ProductController::class, 'destroy']
);

Route::middleware(['auth:sanctum', 'seller'])->get(
    '/seller/products',
    [ProductController::class, 'sellerProducts']
);

Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/auth/user', [AuthController::class, 'user']);

Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post(
    '/cart/items',
    [CartController::class, 'addItem']
);

Route::middleware('auth:sanctum')->get(
    '/cart',
    [CartController::class, 'index']
);

Route::middleware('auth:sanctum')->delete(
    '/cart/items/{id}',
    [CartController::class, 'removeItem']
);

Route::middleware('auth:sanctum')->put(
    '/cart/items/{id}',
    [CartController::class, 'updateItem']
);

Route::middleware('auth:sanctum')->post(
    '/checkout',
    [CheckoutController::class, 'store']
);

Route::middleware('auth:sanctum')->get(
    '/orders',
    [OrderController::class, 'index']
);

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->post(
    '/checkout/session',
    [StripeCheckoutController::class, 'createSession']
);

Route::post(
    '/stripe/webhook',
    [StripeWebhookController::class, 'handle']
);



