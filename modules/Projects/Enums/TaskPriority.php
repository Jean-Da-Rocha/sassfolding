<?php

declare(strict_types=1);

namespace Modules\Projects\Enums;

use Modules\Core\Enums\FlashMessage;
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

    public function color(): FlashMessage
    {
        return match ($this) {
            self::High => FlashMessage::Warning,
            self::Low => FlashMessage::Neutral,
            self::Medium => FlashMessage::Info,
            self::Urgent => FlashMessage::Error,
        };
    }

    /** @return array<string, string> */
    public static function colorMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $priority) => [$priority->value => $priority->color()->value],
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
