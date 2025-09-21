<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Spatie\LaravelData\Data;

final class FlashData extends Data
{
    /** @param array<array-key, FlashMessageData> $messages */
    public function __construct(
        public readonly array $messages = [],
    ) {}
}
