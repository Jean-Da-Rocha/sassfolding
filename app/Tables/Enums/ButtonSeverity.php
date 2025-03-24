<?php

declare(strict_types=1);

namespace App\Tables\Enums;

enum ButtonSeverity: string
{
    case Contrast = 'contrast';
    case Danger = 'danger';
    case Help = 'help';
    case Info = 'info';
    case Secondary = 'secondary';
    case Success = 'success';
    case Warning = 'warn';
}
