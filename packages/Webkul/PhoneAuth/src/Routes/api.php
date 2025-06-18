<?php

use Illuminate\Support\Facades\Route;
use Webkul\PhoneAuth\Http\Controllers\Api\AuthController;

// Apply 'api' middleware to all routes to ensure proper API handling (CSRF bypass, etc.)
Route::group(['middleware' => ['api']], function () {
    Route::prefix('api/phone-auth')->group(function () {
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
