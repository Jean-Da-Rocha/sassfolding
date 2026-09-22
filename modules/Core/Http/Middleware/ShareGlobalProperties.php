<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Hybridly\Hybridly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Core\Data\AppData;
use Modules\Core\Data\FlashData;
use Modules\Core\Data\RouteData;
use Modules\Core\Data\SharedData;
use Modules\Core\Enums\FlashMessage;
use Modules\Users\Data\UserData;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hybridly's own middleware handles the protocol and is registered separately in
 * bootstrap/app.php. Sharing data belongs to a dedicated middleware, as documented in
 * https://hybridly.dev/guide/global-properties.html
 */
final readonly class ShareGlobalProperties
{
    public function __construct(
        private Hybridly $hybridly,
    ) {}

    public function __invoke(Request $request, Closure $next): Response
    {
        $this->hybridly->share(new SharedData(
            app: new AppData(name: str(config()->string('app.name'))->title()->toString()),
            authenticatedUser: UserData::optional(auth()->user()),
            flash: new FlashData(
                error: $request->session()->get(FlashMessage::Error->value),
                info: $request->session()->get(FlashMessage::Info->value),
                neutral: $request->session()->get(FlashMessage::Neutral->value),
                primary: $request->session()->get(FlashMessage::Primary->value),
                secondary: $request->session()->get(FlashMessage::Secondary->value),
                success: $request->session()->get(FlashMessage::Success->value),
                warning: $request->session()->get(FlashMessage::Warning->value),
            ),
            route: new RouteData(name: Route::currentRouteName()),
        ));

        return $next($request);
    }
}
