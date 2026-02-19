<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

describe('deactivate users', function () {
    it('deactivates selected users by nullifying email_verified_at', function () {
        $authenticatedUser = User::factory()->create(['email_verified_at' => now()]);
        $usersToDeactivate = User::factory()->count(2)->create([
            'email_verified_at' => now(),
        ]);

        actingAs($authenticatedUser)
            ->post(route('users.deactivate'), [
                'only' => $usersToDeactivate->pluck('id')->all(),
            ])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '2 user(s) successfully deactivated');

        $usersToDeactivate->each(fn (User $user) => expect($user->fresh()?->email_verified_at)->toBeNull());
    });

    it('does not affect users outside the selection', function () {
        $authenticatedUser = User::factory()->create(['email_verified_at' => now()]);
        $userToDeactivate = User::factory()->create(['email_verified_at' => now()]);

        actingAs($authenticatedUser)
            ->post(route('users.deactivate'), [
                'only' => [$userToDeactivate->id],
            ])
            ->assertRedirect();

        expect($userToDeactivate->fresh()?->email_verified_at)->toBeNull()
            ->and($authenticatedUser->fresh()?->email_verified_at)->not->toBeNull();
    });
});
