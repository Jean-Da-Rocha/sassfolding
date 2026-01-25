<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Modules\Users\Data\UserData;
use Spatie\LaravelData\Data;

final class SharedData extends Data
{
    /** @param array<string, string> $errors */
    public function __construct(
        public readonly AppData $app,
        public readonly ?FlashData $flash,
        public readonly RouteData $route,
        public readonly ?UserData $user,
        public readonly array $errors = [],
    ) {}
}
