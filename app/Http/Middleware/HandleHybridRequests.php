<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Data\BackofficeUserData;
use App\Data\FlashData;
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
            flash: new FlashData(
                error: $request->session()->get(FlashMessage::Error->value),
                info: $request->session()->get(FlashMessage::Info->value),
                success: $request->session()->get(FlashMessage::Success->value),
                warn: $request->session()->get(FlashMessage::Warning->value),
            ),
            route: new RouteData(name: Route::currentRouteName()),
            security: new SecurityData(
                user: UserData::optional(auth()->user()),
            ),
        );
    }
}
