<?php

declare(strict_types=1);

namespace Modules\Users\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data
{
    public function __construct(
        public readonly Optional|int $id,
        #[Email, Max(255)]
        public readonly string $email,
        public readonly Optional|Carbon|null $emailVerifiedAt,
        #[Max(255)]
        public readonly string $name,
        public readonly Optional|string $nameInitial,
    ) {}
}
