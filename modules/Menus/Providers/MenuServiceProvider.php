<?php

declare(strict_types=1);

namespace Modules\Menus\Providers;

use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(Hybridly $hybridly): void
    {
        $hybridly->loadComponentsFrom(base_path('modules/Menus/Resources/Components'));
        $hybridly->loadLayoutsFrom(base_path('modules/Menus/Resources/Layouts'));
        $hybridly->loadViewsFrom(base_path('modules/Menus/Resources/Views'));
    }
}
