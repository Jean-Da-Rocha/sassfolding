<?php

declare(strict_types=1);

use Modules\Authentication\Providers\AuthenticationServiceProvider;
use Modules\Core\Providers\AppServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Core\Providers\HorizonServiceProvider;
use Modules\Datatables\Providers\DatatableServiceProvider;
use Modules\Menus\Providers\MenuServiceProvider;
use Modules\Users\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,
    AuthenticationServiceProvider::class,
    CoreServiceProvider::class,
    DatatableServiceProvider::class,
    HorizonServiceProvider::class,
    MenuServiceProvider::class,
    UserServiceProvider::class,
];
