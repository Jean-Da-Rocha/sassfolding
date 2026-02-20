<?php

declare(strict_types=1);

namespace Modules\Projects\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum ProjectStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
    case OnHold = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Archived => 'Archived',
            self::OnHold => 'On Hold',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Archived => 'neutral',
            self::OnHold => 'warning',
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
