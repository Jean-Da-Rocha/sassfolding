<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(Hybridly $hybridly): void
    {
        if ($this->app->runningInConsole()) {
            foreach (glob(__DIR__.'/../Console/Commands/*.php') as $file) {
                $class = 'Modules\\Core\\Console\\Commands\\'.basename($file, '.php');

                if (class_exists($class)) {
                    $this->commands([$class]);
                }
            }
        }

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $hybridly->loadComponentsFrom(base_path('modules/Core/Resources/Components'), 'core');
        $hybridly->loadLayoutsFrom(base_path('modules/Core/Resources/Layouts'), 'core');
    }
}
