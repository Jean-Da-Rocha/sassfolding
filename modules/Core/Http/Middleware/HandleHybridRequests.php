<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Hybridly\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Core\Data\AppData;
use Modules\Core\Data\FlashData;
use Modules\Core\Data\RouteData;
use Modules\Core\Data\SharedData;
use Modules\Core\Enums\FlashMessage;
use Modules\Users\Data\UserData;

class HandleHybridRequests extends Middleware
{
    protected bool $shareFlashNotifications = false;

    /**
     * Defines the properties that are shared to all requests.
     */
    public function share(Request $request): SharedData
    {
        return new SharedData(
            app: new AppData(name: config('app.name')),
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
            user: UserData::optional(auth()->user()),
            // The errors array is used to type hint the 'error' property on the front-end.
            // This property will be overwritten by Hybridly automatically.
            errors: [],
        );
    }
}
