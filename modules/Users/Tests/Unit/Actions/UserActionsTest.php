<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Enums\FlashMessage;
use Modules\Users\Actions\DeleteUserAction;
use Modules\Users\Models\User;

uses(RefreshDatabase::class);

describe('delete user action', function () {
    it('deletes the user from the database', function () {
        $user = User::factory()->create();

        app(DeleteUserAction::class)->execute($user);

        expect(User::query()->where('id', $user->id)->exists())->toBeFalse();
    });

    it('flashes a success message with the user\'s name', function () {
        $user = User::factory()->create(['name' => 'John Doe']);

        app(DeleteUserAction::class)->execute($user);

        expect(session()->get(FlashMessage::Success->value))->toBe('User "John Doe" successfully deleted');
    });
});
