<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class HorizonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var array<array-key, string> $uriPatterns */
        $uriPatterns = config('horizon.uri_patterns');
        /** @var string $appName */
        $appName = config('app.name');
        $horizonHostName = str('horizon.')->append($appName)->append('.test')->value();

        if ($request->getHost() !== $horizonHostName) {
            return $next($request);
        }

        $isRequestMatchingHorizonUriPattern = collect($uriPatterns)
            ->when(
                $request->ajax(),
                fn (Collection $uriPatterns) => $uriPatterns
                    ->merge(['batches/retry/*', 'jobs/retry/*'])
                    ->map(fn (string $uriPattern) => str($uriPattern)->prepend('api/')->value()),
                fn (Collection $uriPatterns) => $uriPatterns->merge(['/']),
            )
            ->some(fn (string $uriPattern): bool => $request->is($uriPattern));

        if ($isRequestMatchingHorizonUriPattern) {
            return $next($request);
        }

        abort(Response::HTTP_NOT_FOUND);
    }
}
