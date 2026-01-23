<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum FlashMessage: string
{
    case Error = 'error';
    case Info = 'info';
    case Neutral = 'neutral';
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Success = 'success';
    case Warning = 'warning';
}
