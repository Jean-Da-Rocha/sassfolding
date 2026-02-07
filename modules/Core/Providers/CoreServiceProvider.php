<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public const string MODULE_NAMESPACE = 'core';

    public function boot(Hybridly $hybridly): void
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

        $hybridly->loadLayoutsFrom(base_path('modules/Core/Resources/Layouts'), self::MODULE_NAMESPACE);
    }
}
