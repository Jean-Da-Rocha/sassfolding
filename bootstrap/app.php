<?php

declare(strict_types=1);

use Hybridly\HandleHybridRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\Core\Http\Middleware\ShareGlobalProperties;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(health: '/up')
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->appendToGroup('web', [
            HandleHybridRequests::class,
            ShareGlobalProperties::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
