<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Hybridly\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Core\Data\FlashData;
use Modules\Core\Data\FlashMessageData;
use Modules\Core\Data\RouteData;
use Modules\Core\Data\SecurityData;
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
            flash: new FlashData(
                messages: [
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessage::Error->value),
                        severity: FlashMessage::Error->value,
                    ),
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessage::Info->value),
                        severity: FlashMessage::Info->value,
                    ),
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessage::Success->value),
                        severity: FlashMessage::Success->value
                    ),
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessage::Warning->value),
                        severity: FlashMessage::Warning->value,
                    ),
                ],
            ),
            route: new RouteData(name: Route::currentRouteName()),
            security: new SecurityData(
                user: UserData::optional(auth()->user()),
            ),
            // The errors array is used to type hint the 'error' property on the front-end.
            // This property will be overwritten by Hybridly automatically.
            errors: [],
        );
    }
}
