<?php

declare(strict_types=1);

namespace Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;
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

class UserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Fortify::createUsersUsing(CreateNewUserAction::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformationAction::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPasswordAction::class);
        Fortify::resetUserPasswordsUsing(ResetUserPasswordAction::class);

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

        $this->app->register(RouteServiceProvider::class);
    }
}
