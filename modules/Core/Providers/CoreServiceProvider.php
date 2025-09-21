<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
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
    }
}
