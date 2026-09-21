<?php

declare(strict_types=1);

namespace Modules\Authentication\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::verifyEmailView(fn () => hybridly()->view('authentication::verify-email'));
        Fortify::loginView(fn () => hybridly()->view('authentication::login'));
        Fortify::requestPasswordResetLinkView(fn () => hybridly()->view('authentication::forgot-password'));
        Fortify::registerView(fn () => hybridly()->view('authentication::register'));
        Fortify::resetPasswordView(function (Request $request) {
            return hybridly()->view('authentication::reset-password', [
                'token' => $request->route('token'),
                'email' => $request->input('email'),
            ]);
        });

        RateLimiter::for('login', function (Request $request) {
            $fortifyUsername = $request->string(Fortify::username())->value();

            $throttleKey = Str::transliterate(Str::lower($fortifyUsername).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
