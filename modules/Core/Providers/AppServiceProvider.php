<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Carbon\CarbonImmutable;
use Hybridly\Hybridly;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\CliDumper;
use Illuminate\Foundation\Http\HtmlDumper;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(Hybridly $hybridly): void
    {
        URL::forceScheme('https');

        HtmlDumper::dontIncludeSource();
        CliDumper::dontIncludeSource();
        Validator::excludeUnvalidatedArrayKeys();
        Model::shouldBeStrict();
        Model::unguard();
        Date::use(CarbonImmutable::class);

        $hybridly->loadModulesFrom(resource_path('modules'));
        $hybridly->loadLayoutsFrom(resource_path('modules/shared'));
    }
}
