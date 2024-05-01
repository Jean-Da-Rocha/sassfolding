<?php

declare(strict_types=1);

namespace App\Enums;

enum Environment: string
{
    case Local = 'local';

    case Production = 'production';

    case Testing = 'testing';
}
