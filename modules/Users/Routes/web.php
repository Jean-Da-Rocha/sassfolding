<?php

declare(strict_types=1);

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UserController;

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('/', fn () => hybridly('authentication::login'));
});

Route::middleware([Authenticate::class, EnsureEmailIsVerified::class])->group(function () {
    Route::get('/', fn () => to_route('profile'));
    Route::get('profile', fn () => hybridly('users::profile'))->name('profile');
    Route::resource('users', UserController::class)->except('show');
});
