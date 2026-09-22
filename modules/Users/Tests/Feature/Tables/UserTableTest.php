<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

describe('user table', function () {
    it('paginates ten records by default', function () {
        $authenticatedUser = User::factory()->create();

        User::factory()->count(24)->create();

        actingAs($authenticatedUser)
            ->get(route('users.index'))
            ->assertOk()
            ->assertHybridProperties([
                'users.records' => 10,
                'users.paginator.meta.total' => 25,
            ]);
    });

    it('honours the per_page query parameter', function () {
        $authenticatedUser = User::factory()->create();

        User::factory()->count(24)->create();

        actingAs($authenticatedUser)
            ->get(route('users.index', ['per_page' => 25]))
            ->assertHybridProperties(['users.records' => 25]);
    });
});
