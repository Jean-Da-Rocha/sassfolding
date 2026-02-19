<?php

declare(strict_types=1);

namespace Modules\Datatables\Data;

use Modules\Core\Enums\FlashMessage;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class ActionMetaData extends Data
{
    public function __construct(
        public readonly ?FlashMessage $color = null,
        public readonly bool $confirm = false,
        public readonly ?string $confirmMessage = null,
        public readonly ?string $icon = null,
    ) {}
}
