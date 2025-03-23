<?php

declare(strict_types=1);

use App\Enums\FlashMessage;
use App\Exceptions\CannotCreateModelException;
use App\Exceptions\CannotDeleteModelException;
use App\Exceptions\CannotUpdateModelException;
use App\Http\Middleware\HandleHybridRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [HandleHybridRequests::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(
            function (CannotCreateModelException|CannotDeleteModelException|CannotUpdateModelException $exception) {
                return back()->with(FlashMessage::Warning->value, $exception->getMessage());
            });
    })->create();
