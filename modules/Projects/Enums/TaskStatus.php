<?php

declare(strict_types=1);

namespace Modules\Projects\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum TaskStatus: string
{
    case Cancelled = 'cancelled';
    case Done = 'done';
    case InProgress = 'in_progress';
    case Todo = 'todo';

    public function label(): string
    {
        return match ($this) {
            self::Cancelled => 'Cancelled',
            self::Done => 'Done',
            self::InProgress => 'In Progress',
            self::Todo => 'Todo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Cancelled => 'error',
            self::Done => 'success',
            self::InProgress => 'info',
            self::Todo => 'neutral',
        };
    }

    /** @return array<string, string> */
    public static function colorMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $status) => [$status->value => $status->color()],
        )->all();
    }

    /** @return array<string, string> */
    public static function labelMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $status) => [$status->value => $status->label()],
        )->all();
    }
}
