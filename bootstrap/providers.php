<?php

declare(strict_types=1);

use Modules\Core\Providers\AppServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Core\Providers\HorizonServiceProvider;
use Modules\Users\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,
    HorizonServiceProvider::class,
    UserServiceProvider::class,
    CoreServiceProvider::class,
];
