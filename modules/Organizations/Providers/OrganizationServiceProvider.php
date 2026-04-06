<?php

declare(strict_types=1);

namespace Modules\Organizations\Providers;

use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;

class OrganizationServiceProvider extends ServiceProvider
{
    const string MODULE_NAMESPACE = 'organizations';

    public function boot(Hybridly $hybridly): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->register(RouteServiceProvider::class);

        $hybridly->loadViewsFrom(base_path('modules/Organizations/Resources/Views'), self::MODULE_NAMESPACE);
    }
}
