<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/shop/contact', 'middleware' => ['api']], function () {
    Route::post('landscape-request', [
        Webkul\Contact\Http\Controllers\Shop\LandscapeRequestController::class,
        'store',
    ]);
    Route::post('contact-us', [
        Webkul\Contact\Http\Controllers\Shop\ContactUsController::class,
        'store',
    ]);
});
