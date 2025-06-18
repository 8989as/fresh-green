<?php

use Illuminate\Support\Facades\Route;

// Diagnostic route outside the middleware for testing
Route::get('contact-diagnostic', function() {
    try {
        $controller = app()->make(\Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class);
        $response = $controller->index();
        return response()->json(['success' => true, 'responseType' => get_class($response), 'message' => 'Controller executed successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }
})->name('public.contact.diagnostic');

Route::group(['prefix' => 'admin/contact', 'middleware' => ['web', 'admin']], function () {
    Route::get('landscape-requests', [
        \Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class,
        'index',
    ])->name('admin.contact.landscape-requests.index');
    Route::delete('landscape-requests/{id}', [
        \Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class,
        'destroy',
    ])->name('admin.contact.landscape-requests.destroy');

    Route::get('contact-us', [
        \Webkul\Contact\Http\Controllers\Admin\ContactUsController::class,
        'index',
    ])->name('admin.contact.contact-us.index');
    Route::delete('contact-us/{id}', [
        \Webkul\Contact\Http\Controllers\Admin\ContactUsController::class,
        'destroy',
    ])->name('admin.contact.contact-us.destroy');
    
    // Simple test route to verify that routes are being loaded
    Route::get('test', function() {
        return response()->json(['message' => 'Contact package routes are working!']);
    })->name('admin.contact.test');
    
    // Diagnostic route to test controller and view rendering
    Route::get('diagnostic', function() {
        try {
            $controller = app()->make(\Webkul\Contact\Http\Controllers\Admin\LandscapeRequestController::class);
            $response = $controller->index();
            return response()->json(['success' => true, 'responseType' => get_class($response), 'message' => 'Controller executed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    })->name('admin.contact.diagnostic');
});
