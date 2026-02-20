<?php

declare(strict_types=1);

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\BulkDeleteOrganizationController;
use Modules\Organizations\Http\Controllers\OrganizationController;

Route::middleware([Authenticate::class, EnsureEmailIsVerified::class])->group(function () {
    Route::resource('organizations', OrganizationController::class)->except('show');
    Route::post('organizations/bulk-delete', BulkDeleteOrganizationController::class)->name('organizations.bulk-delete');
});
