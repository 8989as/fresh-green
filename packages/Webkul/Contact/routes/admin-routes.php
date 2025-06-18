<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/admin/contact', 'middleware' => ['api', 'auth:admin']], function () {
    Route::get('landscape-requests', [
        Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class,
        'index',
    ]);
    Route::get('landscape-requests/{id}', [
        Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class,
        'show',
    ]);
    Route::delete('landscape-requests/{id}', [
        Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class,
        'destroy',
    ]);

    Route::get('contact-us', [
        Webkul\Contact\Http\Controllers\Admin\ContactUsController::class,
        'index',
    ]);
    Route::get('contact-us/{id}', [
        Webkul\Contact\Http\Controllers\Admin\ContactUsController::class,
        'show',
    ]);
    Route::delete('contact-us/{id}', [
        Webkul\Contact\Http\Controllers\Admin\ContactUsController::class,
        'destroy',
    ]);
});
