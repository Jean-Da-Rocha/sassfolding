<?php

declare(strict_types=1);

namespace App\Tables\Enums;

enum ActionType: string
{
    case Custom = 'custom';
    case Create = 'create';
    case Delete = 'delete';
    case Edit = 'edit';
    case Show = 'show';
}
