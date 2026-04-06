<?php

declare(strict_types=1);

namespace Modules\Projects\Data;

use Carbon\Carbon;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

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
        #[Date, WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d'), WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        public readonly Optional|Carbon|null $dueAt,
        #[Numeric, Min(0)]
        public readonly ?float $estimatedHours,
        public readonly Optional|Carbon|null $completedAt,
        public readonly Optional|Carbon|null $createdAt,
    ) {}
}
