<?php

declare(strict_types=1);

namespace Modules\Organizations\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;

#[MapName(SnakeCaseMapper::class)]
final class OrganizationData extends Data
{
    public function __construct(
        public readonly Optional|int $id,
        #[Max(255)]
        public readonly string $name,
        #[Max(255), Unique(table: 'organizations', column: 'slug', ignore: new RouteParameterReference('organization', 'id', true))]
        public readonly string $slug,
        public readonly Optional|bool $isActive,
        public readonly Optional|CarbonInterface|null $createdAt,
    ) {}
}
