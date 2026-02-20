<?php

declare(strict_types=1);

namespace Modules\Projects\Providers;

use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    const string MODULE_NAMESPACE = 'projects';

    public function boot(Hybridly $hybridly): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->register(RouteServiceProvider::class);

        $hybridly->loadViewsFrom(base_path('modules/Projects/Resources/Views'), self::MODULE_NAMESPACE);
    }
}
