<?php

declare(strict_types=1);

use Modules\Users\Models\User;

return [
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],
    ],
];
