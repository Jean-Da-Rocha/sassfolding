<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $commandFiles = glob(__DIR__.'/../Console/Commands/*.php');

        if (is_array($commandFiles)) {
            foreach ($commandFiles as $file) {
                $class = 'Modules\\Core\\Console\\Commands\\'.basename($file, '.php');

                if (class_exists($class)) {
                    $this->commands([$class]);
                }
            }
        }

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
