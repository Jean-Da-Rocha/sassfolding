<?php

declare(strict_types=1);

return [
    'server' => env('OCTANE_SERVER', 'frankenphp'),

    'watch' => [
        'app',
        'bootstrap',
        'config/**/*.php',
        'database/**/*.php',
        'modules/**/*.php',
        'public/**/*.php',
        'resources/**/*.php',
        'routes',
        'composer.lock',
        '.env',
    ],
];
