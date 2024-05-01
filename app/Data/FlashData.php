<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

final class FlashData extends Data
{
    public function __construct(
        public readonly mixed $error,
        public readonly mixed $info,
        public readonly mixed $status,
        public readonly mixed $success,
        public readonly mixed $warn,
    ) {
    }
}
