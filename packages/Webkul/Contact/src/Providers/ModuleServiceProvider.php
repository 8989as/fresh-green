<?php

namespace Webkul\Contact\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * The aliased name of this module.
     */
    protected $moduleName = 'Contact';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ContactServiceProvider::class);
    }
    
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();
    }
}
