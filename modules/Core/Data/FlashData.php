<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Spatie\LaravelData\Data;

final class FlashData extends Data
{
    public function __construct(
        public readonly mixed $error,
        public readonly mixed $info,
        public readonly mixed $neutral,
        public readonly mixed $primary,
        public readonly mixed $secondary,
        public readonly mixed $success,
        public readonly mixed $warning,
    ) {}
}
