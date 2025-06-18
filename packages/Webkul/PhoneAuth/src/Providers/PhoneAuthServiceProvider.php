<?php

namespace Webkul\PhoneAuth\Providers;

use Illuminate\Support\ServiceProvider;

class PhoneAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'phoneauth');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'phoneauth');

        $this->publishes([
            __DIR__.'/../Config/phoneauth.php' => config_path('phoneauth.php'),
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->bind(\Webkul\Customer\Models\Customer::class, \Webkul\PhoneAuth\Models\Customer::class);
        $this->app['router']->aliasMiddleware('phone.verified', \Webkul\PhoneAuth\Http\Middleware\PhoneVerified::class);
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/phoneauth.php', 'phoneauth'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}