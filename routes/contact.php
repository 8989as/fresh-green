<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Contact Routes
|--------------------------------------------------------------------------
|
| Here is where you can register contact routes for your application.
| These routes are loaded by the ContactServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::group(['prefix' => 'admin/contact', 'middleware' => ['web', 'admin']], function () {
    Route::get('landscape-requests', function () {
        $landscapeRequests = app()->make(\Webkul\Contact\Repositories\LandscapeRequestRepository::class)->all();
        return view('contact::admin.landscape_requests.index', compact('landscapeRequests'));
    })->name('admin.contact.landscape-requests.index');
    
    Route::delete('landscape-requests/{id}', function ($id) {
        app()->make(\Webkul\Contact\Repositories\LandscapeRequestRepository::class)->delete($id);
        return back()->with('success', 'Deleted successfully.');
    })->name('admin.contact.landscape-requests.destroy');

    Route::get('contact-us', function () {
        $contactUs = app()->make(\Webkul\Contact\Repositories\ContactUsRepository::class)->all();
        return view('contact::admin.contact_us.index', compact('contactUs'));
    })->name('admin.contact.contact-us.index');
    
    Route::delete('contact-us/{id}', function ($id) {
        app()->make(\Webkul\Contact\Repositories\ContactUsRepository::class)->delete($id);
        return back()->with('success', 'Deleted successfully.');
    })->name('admin.contact.contact-us.destroy');
});
