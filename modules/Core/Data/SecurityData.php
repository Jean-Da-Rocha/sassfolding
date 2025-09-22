<?php

declare(strict_types=1);

namespace Modules\Core\Data;

use Modules\Users\Data\UserData;
use Spatie\LaravelData\Data;

class SecurityData extends Data
{
    public function __construct(
        public readonly ?UserData $user
    ) {}
}
