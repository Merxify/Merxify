<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Products
    Route::apiResource('products', ProductController::class, ['except' => 'update']);
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Categories
    Route::apiResource('categories', CategoryController::class, ['except' => 'update']);
    Route::patch('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    Route::get('me', [ProfileController::class, 'index'])->middleware('verified');
});
