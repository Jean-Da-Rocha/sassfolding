<?php

declare(strict_types=1);

use Modules\Authentication\Providers\AuthenticationServiceProvider;
use Modules\Core\Providers\AppServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Users\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,
    AuthenticationServiceProvider::class,
    CoreServiceProvider::class,
    UserServiceProvider::class,
];
