<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Spatie\LaravelData\Data;

final class SharedData extends Data
{
    /** @param array<string, string> $errors */
    public function __construct(
        public readonly ?FlashData $flash,
        public readonly RouteData $route,
        public readonly SecurityData $security,
        public readonly array $errors = [],
    ) {}
}
