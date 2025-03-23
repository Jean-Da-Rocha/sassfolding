<?php

declare(strict_types=1);

namespace App\Tables\Data;

use App\Tables\Enums\ButtonSeverity;
use App\Tables\Enums\ButtonSize;
use App\Tables\Enums\ButtonVariant;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ButtonStyleData extends Data
{
    public function __construct(
        public Optional|string|null $icon = null,
        public bool $raised = false,
        public bool $rounded = true,
        public Optional|ButtonSeverity|null $severity = null,
        public Optional|ButtonSize|null $size = null,
        public Optional|string|null $text = null,
        public Optional|ButtonVariant|null $variant = null,
    ) {}
}
