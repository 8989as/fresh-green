<?php

namespace Webkul\PhoneAuth\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\PhoneAuth\Models\CustomerOtp::class,
    ];
}