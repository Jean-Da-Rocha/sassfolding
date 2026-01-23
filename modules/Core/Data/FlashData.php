<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Spatie\LaravelData\Data;

final class FlashData extends Data
{
    public function __construct(
        public readonly ?string $error = null,
        public readonly ?string $info = null,
        public readonly ?string $neutral = null,
        public readonly ?string $primary = null,
        public readonly ?string $secondary = null,
        public readonly ?string $success = null,
        public readonly ?string $warning = null,
    ) {}
}
