<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\Core\Http\Middleware\EnsureValidHorizonUri;
use Modules\Core\Http\Middleware\HandleHybridRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [HandleHybridRequests::class, EnsureValidHorizonUri::class]);
    })
    ->withExceptions(function (\Illuminate\Foundation\Configuration\Exceptions $exceptions) {})
    ->create();
