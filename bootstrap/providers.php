<?php

declare(strict_types=1);

use Modules\Authentication\Providers\AuthenticationServiceProvider;
use Modules\Core\Providers\AppServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Datatables\Providers\DatatableServiceProvider;
use Modules\Organizations\Providers\OrganizationServiceProvider;
use Modules\Projects\Providers\ProjectServiceProvider;
use Modules\Users\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,
    AuthenticationServiceProvider::class,
    CoreServiceProvider::class,
    DatatableServiceProvider::class,
    OrganizationServiceProvider::class,
    ProjectServiceProvider::class,
    UserServiceProvider::class,
];
