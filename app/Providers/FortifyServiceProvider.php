<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\CreateNewUserAction;
use App\Actions\Fortify\ResetUserPasswordAction;
use App\Actions\Fortify\UpdateUserPasswordAction;
use App\Actions\Fortify\UpdateUserProfileInformationAction;
use App\Http\Responses\EmailVerificationNotificationSentResponse;
use App\Http\Responses\ProfileInformationUpdatedResponse;
use App\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse as EmailVerificationNotificationSentResponseContract;
use Laravel\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;
use Laravel\Fortify\Fortify;

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

        $this->app->singleton(ProfileInformationUpdatedResponseContract::class, ProfileInformationUpdatedResponse::class);
        $this->app->singleton(EmailVerificationNotificationSentResponseContract::class, EmailVerificationNotificationSentResponse::class);
        $this->app->singleton(SuccessfulPasswordResetLinkRequestResponseContract::class, SuccessfulPasswordResetLinkRequestResponse::class);
    }
}
