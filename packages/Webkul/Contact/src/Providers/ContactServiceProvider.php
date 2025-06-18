<?php

namespace Webkul\Contact\Providers;

use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Load routes - ensure all route files are loaded correctly
        $this->loadRoutesFrom(__DIR__ . '/../../routes/shop-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin-routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin-web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'contact');
    }

    public function register()
    {
        // Register repositories - using singleton to ensure single instance
        $this->app->singleton(\Webkul\Contact\Repositories\LandscapeRequestRepository::class);
        $this->app->singleton(\Webkul\Contact\Repositories\ContactUsRepository::class);
    }
}
