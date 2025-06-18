<?php

use Illuminate\Support\Facades\Route;
use Webkul\Contact\Http\Controllers\Admin\ContactUsController;
use Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController;

/**
 * Admin web routes for Contact package
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/contact'], function () {
    // Landscape Requests Routes
    Route::get('landscape-requests', [LandscapeRequestController::class, 'index'])
        ->name('admin.contact.landscape-requests.index');
    
    Route::delete('landscape-requests/{id}', [LandscapeRequestController::class, 'destroy'])
        ->name('admin.contact.landscape-requests.destroy');

    // Contact Us Routes
    Route::get('contact-us', [ContactUsController::class, 'index'])
        ->name('admin.contact.contact-us.index');
    
    Route::delete('contact-us/{id}', [ContactUsController::class, 'destroy'])
        ->name('admin.contact.contact-us.destroy');
});
