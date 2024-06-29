<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Data\FlashData;
use App\Data\FlashMessageData;
use App\Data\RouteData;
use App\Data\SecurityData;
use App\Data\SharedData;
use App\Data\UserData;
use App\Enums\FlashMessage;
use Hybridly\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HandleHybridRequests extends Middleware
{
    protected bool $shareFlashNotifications = false;

    /**
     * Defines the properties that are shared to all requests.
     */
    public function share(Request $request): SharedData
    {
        return new SharedData(
            // The errors array is used to type hint the 'error' property on the front-end.
            // This property will be overwritten by Hybridly automatically.
            flash: new FlashData(
                status: new FlashMessageData(
                    message: $request->session()->get(FlashMessage::Status->value),
                    severity: FlashMessage::Status->value
                ),
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
            errors: [],
        );
    }
}
