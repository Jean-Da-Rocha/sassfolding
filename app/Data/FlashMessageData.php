<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class FlashMessageData extends Data
{
    public function __construct(
        public readonly int|float|string $key,
        public readonly mixed $message,
        public readonly string $severity,
    ) {
    }
}
