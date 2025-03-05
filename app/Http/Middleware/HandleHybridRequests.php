<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Data\FlashData;
use App\Data\FlashMessageData;
use App\Data\RouteData;
use App\Data\SecurityData;
use App\Data\SharedData;
use App\Data\UserData;
use App\Enums\FlashMessageEnum;
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
            flash: new FlashData(
                messages: [
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessageEnum::Error->value),
                        severity: FlashMessageEnum::Error->value,
                    ),
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessageEnum::Info->value),
                        severity: FlashMessageEnum::Info->value,
                    ),
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessageEnum::Success->value),
                        severity: FlashMessageEnum::Success->value
                    ),
                    new FlashMessageData(
                        message: $request->session()->get(FlashMessageEnum::Warning->value),
                        severity: FlashMessageEnum::Warning->value,
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
