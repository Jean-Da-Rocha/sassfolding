<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\Core\Http\Middleware\HandleHybridRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(health: '/up')
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->appendToGroup('web', [HandleHybridRequests::class]);
    })
    ->withExceptions(function (\Illuminate\Foundation\Configuration\Exceptions $exceptions) {})
    ->create();
