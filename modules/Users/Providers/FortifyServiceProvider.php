<?php

declare(strict_types=1);

namespace Modules\Users\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse as EmailVerificationNotificationSentResponseContract;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Fortify;
use Modules\Users\Actions\Fortify\CreateNewUserAction;
use Modules\Users\Actions\Fortify\ResetUserPasswordAction;
use Modules\Users\Actions\Fortify\UpdateUserPasswordAction;
use Modules\Users\Actions\Fortify\UpdateUserProfileInformationAction;
use Modules\Users\Http\Responses\EmailVerificationNotificationSentResponse;
use Modules\Users\Http\Responses\ProfileInformationUpdatedResponse;
use Modules\Users\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::verifyEmailView(fn () => hybridly('authentication::verify-email'));
        Fortify::loginView(fn () => hybridly('authentication::login'));
        Fortify::requestPasswordResetLinkView(fn () => hybridly('authentication::forgot-password'));
        Fortify::registerView(fn () => hybridly('authentication::register'));
        Fortify::resetPasswordView(function (Request $request) {
            return hybridly('authentication::reset-password', [
                'token' => $request->route('token'),
                'email' => $request->input('email'),
            ]);
        });

        Fortify::createUsersUsing(CreateNewUserAction::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformationAction::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPasswordAction::class);
        Fortify::resetUserPasswordsUsing(ResetUserPasswordAction::class);

        RateLimiter::for('login', function (Request $request) {
            $fortifyUsername = $request->string(Fortify::username())->value();

            $throttleKey = Str::transliterate(Str::lower($fortifyUsername).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        $this->app->singleton(
            ProfileInformationUpdatedResponseContract::class,
            ProfileInformationUpdatedResponse::class
        );
        $this->app->singleton(
            EmailVerificationNotificationSentResponseContract::class,
            EmailVerificationNotificationSentResponse::class
        );
        $this->app->singleton(
            SuccessfulPasswordResetLinkRequestResponseContract::class,
            SuccessfulPasswordResetLinkRequestResponse::class
        );
    }
}
