<?php

declare(strict_types=1);

namespace Modules\Projects\Data;

use Carbon\CarbonInterface;
use Modules\Projects\Enums\ProjectStatus;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class ProjectData extends Data
{
    public function __construct(
        public readonly Optional|int $id,
        #[Max(255)]
        public readonly string $name,
        public readonly ?string $description,
        public readonly ProjectStatus $status,
        public readonly Optional|int $ownerId,
        #[Exists('organizations', 'id')]
        public readonly int $organizationId,
        public readonly Optional|CarbonInterface|null $createdAt,
    ) {}
}
