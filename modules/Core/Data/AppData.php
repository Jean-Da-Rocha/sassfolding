<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Spatie\LaravelData\Data;

class AppData extends Data
{
    public function __construct(
        public readonly string $name,
    ) {}
}
