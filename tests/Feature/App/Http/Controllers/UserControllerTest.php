<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;

use function Pest\Laravel\actingAs;

describe('users listing', function () {
    it('returns the expected user data on the front-end using the `index` endpoint', function () {
        $authenticatedUser = User::factory()->create();

        User::factory()->count(4)->create();

        actingAs($authenticatedUser)
            ->get(route('users.index'))
            ->assertOk()
            ->assertHybridView('users::user-listing')
            ->assertHybridProperties(['users.records' => 5]);
    });
});
