<?php

declare(strict_types=1);

namespace Modules\Projects\Data;

use Carbon\CarbonInterface;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class TaskData extends Data
{
    public function __construct(
        public readonly Optional|int $id,
        #[Exists('projects', 'id')]
        public readonly int $projectId,
        #[Max(255)]
        public readonly string $title,
        public readonly ?string $description,
        public readonly TaskStatus $status,
        public readonly TaskPriority $priority,
        public readonly Optional|bool $isPinned,
        #[Date]
        public readonly Optional|CarbonInterface|string|null $dueAt,
        #[Numeric, Min(0)]
        public readonly ?float $estimatedHours,
        public readonly Optional|CarbonInterface|null $completedAt,
        public readonly Optional|CarbonInterface|null $createdAt,
    ) {}
}
