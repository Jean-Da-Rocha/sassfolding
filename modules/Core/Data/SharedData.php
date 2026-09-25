<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Modules\Users\Data\UserData;
use Spatie\LaravelData\Data;

final class SharedData extends Data
{
    public function __construct(
        public readonly AppData $app,
        public readonly ?UserData $authenticatedUser,
        public readonly ?FlashData $flash,
        public readonly RouteData $route,
    ) {}
}
