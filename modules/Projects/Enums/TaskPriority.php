<?php

declare(strict_types=1);

namespace Modules\Projects\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum TaskPriority: string
{
    case High = 'high';
    case Low = 'low';
    case Medium = 'medium';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::High => 'High',
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::Urgent => 'Urgent',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::High => 'warning',
            self::Low => 'neutral',
            self::Medium => 'info',
            self::Urgent => 'error',
        };
    }

    /** @return array<string, string> */
    public static function colorMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $priority) => [$priority->value => $priority->color()],
        )->all();
    }

    /** @return array<string, string> */
    public static function labelMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $priority) => [$priority->value => $priority->label()],
        )->all();
    }
}
