<?php

use Illuminate\Support\Facades\Route;
use Webkul\PhoneAuth\Http\Controllers\Shop\PhoneAuthController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'phoneauth'], function () {
    Route::get('', [PhoneAuthController::class, 'index'])->name('shop.phoneauth.index');
});