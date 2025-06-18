<?php

use Illuminate\Support\Facades\Route;
use Webkul\PhoneAuth\Http\Controllers\Api\AuthController;

// Use the web middleware group for API routes, as per Bagisto's convention
Route::group(['middleware' => ['web', 'api'], 'prefix' => 'api'], function () {
    Route::prefix('phone-auth')->group(function () {
        Route::post('send-otp', [AuthController::class, 'sendOtp']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            // Add more protected routes here
        });
    });
});
