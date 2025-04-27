<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum FlashMessage: string
{
    case Contrast = 'contrast';
    case Error = 'error';
    case Info = 'info';
    case Secondary = 'secondary';
    case Success = 'success';
    case Warning = 'warn';
}
