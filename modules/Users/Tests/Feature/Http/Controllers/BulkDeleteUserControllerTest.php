<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

describe('bulk delete', function () {
    it('deletes selected users', function () {
        $authenticatedUser = User::factory()->create(['email_verified_at' => now()]);
        $usersToDelete = User::factory()->count(3)->create();

        actingAs($authenticatedUser)
            ->post(route('users.bulk-delete'), [
                'only' => $usersToDelete->pluck('id')->all(),
            ])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '3 user(s) successfully deleted');

        expect(User::query()->count())->toBe(1)
            ->and(User::query()->sole()->id)->toBe($authenticatedUser->id);
    });

    it('deletes all users when "all" is true', function () {
        $authenticatedUser = User::factory()->create(['email_verified_at' => now()]);
        User::factory()->count(2)->create();

        actingAs($authenticatedUser)
            ->post(route('users.bulk-delete'), ['all' => true])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '3 user(s) successfully deleted');

        expect(User::query()->count())->toBe(0);
    });
});
