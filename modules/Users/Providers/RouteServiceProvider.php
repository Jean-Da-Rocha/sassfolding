<?php

declare(strict_types=1);

namespace Modules\Users\Providers;

use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends \Illuminate\Foundation\Support\Providers\RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(fn () => Route::middleware('web')->group(__DIR__.'/../Routes/web.php'));
    }
}
