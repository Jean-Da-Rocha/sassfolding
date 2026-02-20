<?php

declare(strict_types=1);

namespace Modules\Organizations\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum OrganizationRole: string
{
    case Admin = 'admin';
    case Guest = 'guest';
    case Member = 'member';
}
