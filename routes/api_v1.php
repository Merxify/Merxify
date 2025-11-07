<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\AttributeController;
use App\Http\Controllers\Api\V1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\V1\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Api\V1\Auth\NewPasswordController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\V1\Auth\RegisteredUserController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductVariantController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);
        Route::post('/register', [RegisteredUserController::class, 'store']);
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
        Route::post('/reset-password', [NewPasswordController::class, 'store']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
            ->name('verification.verify');
        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->name('verification.send');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        // Category Routes
        Route::apiResource('categories', CategoryController::class);
        // Product Routes
        Route::apiResource('products', ProductController::class);
        // ProductVariant Routes
        Route::apiResource('product-variants', ProductVariantController::class);
        // User Routes
        Route::apiResource('users', UserController::class);
        // Address Routes
        Route::apiResource('addresses', AddressController::class);
        // Attribute Routes
        Route::apiResource('attributes', AttributeController::class);
        // Cart Routes
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('items', [CartController::class, 'addItem']);
            Route::put('items/{id}', [CartController::class, 'updateItem']);
            Route::delete('items/{id}', [CartController::class, 'removeItem']);
            Route::delete('clear', [CartController::class, 'clear']);
        });
        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']);
            Route::get('{id}', [OrderController::class, 'show']);
            Route::post('{id}/cancel', [OrderController::class, 'cancel']);
            Route::put('{id}/status', [OrderController::class, 'updateStatus']);
        });
    });
});
