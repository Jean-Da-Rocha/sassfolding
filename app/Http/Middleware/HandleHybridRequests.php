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
use Illuminate\Support\Str;

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
                error: new FlashMessageData(
                    key: Str::random(32),
                    message: $request->session()->get(FlashMessage::Error->value),
                    severity: 'error'
                ),
                info: new FlashMessageData(
                    key: Str::random(32),
                    message: $request->session()->get(FlashMessage::Info->value),
                    severity: FlashMessage::Info->value,
                ),
                status: new FlashMessageData(
                    key: Str::random(32),
                    message: $request->session()->get(FlashMessage::Status->value),
                    severity: FlashMessage::Status->value
                ),
                success: new FlashMessageData(
                    key: Str::random(32),
                    message: $request->session()->get(FlashMessage::Success->value),
                    severity: FlashMessage::Success->value
                ),
                warn: new FlashMessageData(
                    key: Str::random(32),
                    message: $request->session()->get(FlashMessage::Warning->value),
                    severity: FlashMessage::Warning->value,
                ),

            ),
            route: new RouteData(name: Route::currentRouteName()),
            security: new SecurityData(
                user: UserData::optional(auth()->user()),
            ),
        );
    }
}
