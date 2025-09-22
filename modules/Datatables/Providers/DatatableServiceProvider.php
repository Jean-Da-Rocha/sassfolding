<?php

declare(strict_types=1);

namespace Modules\Datatables\Providers;

use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;

class DatatableServiceProvider extends ServiceProvider
{
    public function boot(Hybridly $hybridly): void
    {
        $hybridly->loadComponentsFrom(base_path('modules/Datatables/Resources/Components'));
        $hybridly->loadLayoutsFrom(base_path('modules/Datatables/Resources/Layouts'));
        $hybridly->loadViewsFrom(base_path('modules/Datatables/Resources/Views'));
    }
}
