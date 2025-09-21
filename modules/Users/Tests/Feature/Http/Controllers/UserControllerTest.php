<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Users\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

describe('users listing', function () {
    it('lists all of the users', function () {
        $authenticatedUser = User::factory()->create();

        User::factory()->count(4)->create();

        actingAs($authenticatedUser)
            ->get(route('users.index'))
            ->assertOk()
            ->assertHybridView('users::list-users')
            ->assertHybridProperties(['users.records' => 5]);
    });

    it('shows the view for creating a new user', function () {
        $authenticatedUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->get('/users/create')
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('users::create-user')
            ->assertHybridProperties([]);
    });

    it('stores a new user in storage and returns a success message', function () {
        $authenticatedUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->post('/users', [
                'email' => 'dummy@user.com',
                'name' => 'Dummy User',
                'password' => 'dummy_password',
                'password_confirmation' => 'dummy_password',
            ])
            ->assertStatus(Response::HTTP_SEE_OTHER)
            ->assertRedirect('/users')
            ->assertSessionHas(FlashMessage::Success->value, 'User "Dummy User" successfully created');

        assertDatabaseHas('users', ['email' => 'dummy@user.com', 'name' => 'Dummy User']);
    });

    it('shows the view for editing an existing user', function () {
        $authenticatedUser = User::factory()->create();
        $existingUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->get(sprintf('users/%d/edit', $existingUser->id))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('users::edit-user')
            ->assertHybridProperties([
                'user.email' => $existingUser->email,
                'user.name' => $existingUser->name,
            ]);
    });

    it('updates an existing user and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $userToUpdate = User::factory()->create(['name' => 'Dummy User']);

        $updatedAttributes = [...$userToUpdate->toArray(), 'name' => 'Dummy User Updated'];

        actingAs($authenticatedUser)
            ->put(sprintf('/users/%d', $userToUpdate->id), $updatedAttributes)
            ->assertStatus(Response::HTTP_SEE_OTHER)
            ->assertRedirect('/users')
            ->assertSessionHas(FlashMessage::Success->value, 'User "Dummy User Updated" successfully updated');

        assertDatabaseMissing('users', ['name' => 'Dummy User']);
        assertDatabaseHas('users', ['name' => 'Dummy User Updated']);
    });

    it('deletes an existing user and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $userToDelete = User::factory()->create(['name' => 'Dummy User']);

        actingAs($authenticatedUser)
            ->delete(sprintf('users/%d', $userToDelete->id))
            ->assertStatus(Response::HTTP_SEE_OTHER)
            ->assertRedirect('/users')
            ->assertSessionHas(FlashMessage::Success->value, 'User "Dummy User" successfully deleted');
    });
});
