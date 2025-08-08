<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'store']);
    Route::post('/register', [RegistrationController::class, 'store']);
    Route::post('/reset-password', [NewPasswordController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::post('/logout', [AuthenticationController::class, 'destroy']);
});

include __DIR__.'/api_v1.php';
