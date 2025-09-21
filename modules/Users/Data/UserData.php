<?php

declare(strict_types=1);

namespace Modules\Users\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $nameInitial,
        public readonly string $email,
        public readonly ?CarbonInterface $email_verified_at,
    ) {}
}
