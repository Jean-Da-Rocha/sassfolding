<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum FlashMessage: string
{
    case Error = 'error';
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warn';
}
