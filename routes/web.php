<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteTestController;
use Webkul\PhoneAuth\Http\Controllers\Api\AuthController;

// Temporary route for debugging
Route::get('/debug/routes', [RouteTestController::class, 'checkRoutes']);

// Comment out test routes as we should now test the actual API endpoints
// Route::middleware(['api'])->prefix('test-phone-auth')->group(function () {
//     Route::post('register', [AuthController::class, 'register']);
//     Route::post('send-otp', [AuthController::class, 'sendOtp']);
// });
