<?php

use Illuminate\Support\Facades\Route;
use Webkul\PhoneAuth\Http\Controllers\Admin\PhoneAuthController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/phoneauth'], function () {
    Route::controller(PhoneAuthController::class)->group(function () {
        Route::get('', 'index')->name('admin.phoneauth.index');
    });
});