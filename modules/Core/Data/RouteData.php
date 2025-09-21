<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Spatie\LaravelData\Data;

final class RouteData extends Data
{
    public function __construct(
        public readonly ?string $name = null,
    ) {}
}
