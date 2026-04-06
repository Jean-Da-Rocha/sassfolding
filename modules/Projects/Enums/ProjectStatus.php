<?php

declare(strict_types=1);

namespace Modules\Projects\Enums;

use Modules\Core\Enums\FlashMessage;
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

    public function color(): FlashMessage
    {
        return match ($this) {
            self::Active => FlashMessage::Success,
            self::Archived => FlashMessage::Neutral,
            self::OnHold => FlashMessage::Warning,
        };
    }

    /** @return array<string, string> */
    public static function colorMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $status) => [$status->value => $status->color()->value],
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
