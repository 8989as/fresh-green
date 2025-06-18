<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ContactPackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(\Webkul\Contact\Providers\ModuleServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // We'll let the package's own service provider handle route loading
        // Just to make sure our admin-web routes are loaded
        $this->loadRoutesFrom(base_path('packages/Webkul/Contact/routes/admin-web.php'));
    }
}
