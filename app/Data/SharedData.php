<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

final class SharedData extends Data
{
    public function __construct(
        public readonly ?FlashData $flash,
        public readonly RouteData $route,
        public readonly SecurityData $security,
    ) {
    }
}
