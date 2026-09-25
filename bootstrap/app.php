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
        // Trusting every proxy lets any client forge X-Forwarded-For and defeat rate limiters
        // keyed on the IP, starting with Fortify's login throttle. Narrow this in production.
        $middleware->trustProxies(at: [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        ]);
        $middleware->appendToGroup('web', [
            HandleHybridRequests::class,
            ShareGlobalProperties::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
