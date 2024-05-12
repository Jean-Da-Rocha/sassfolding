<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

final class FlashData extends Data
{
    public function __construct(
        public readonly FlashMessageData $error,
        public readonly FlashMessageData $info,
        public readonly FlashMessageData $status,
        public readonly FlashMessageData $success,
        public readonly FlashMessageData $warn,
    ) {
    }
}
