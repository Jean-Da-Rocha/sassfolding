<?php

declare(strict_types=1);

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;
use Modules\Projects\Http\Controllers\BulkDeleteProjectController;
use Modules\Projects\Http\Controllers\BulkDeleteTaskController;
use Modules\Projects\Http\Controllers\ProjectController;
use Modules\Projects\Http\Controllers\TaskController;

Route::middleware([Authenticate::class, EnsureEmailIsVerified::class])->group(function () {
    Route::resource('projects', ProjectController::class)->except('show');
    Route::post('projects/bulk-delete', BulkDeleteProjectController::class)->name('projects.bulk-delete');

    Route::resource('tasks', TaskController::class)->except('show');
    Route::post('tasks/bulk-delete', BulkDeleteTaskController::class)->name('tasks.bulk-delete');
});
