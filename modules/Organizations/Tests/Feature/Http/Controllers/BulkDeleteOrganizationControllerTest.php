<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Organizations\Models\Organization;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

describe('bulk delete organizations', function () {
    it('deletes selected organizations', function () {
        $authenticatedUser = User::factory()->create();
        $organizationsToDelete = Organization::factory()->count(3)->create();

        actingAs($authenticatedUser)
            ->post(route('organizations.bulk-delete'), [
                'only' => $organizationsToDelete->pluck('id')->all(),
            ])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '3 organization(s) successfully deleted');

        expect(Organization::query()->count())->toBe(0);
    });

    it('deletes all organizations when "all" is true', function () {
        $authenticatedUser = User::factory()->create();
        Organization::factory()->count(2)->create();

        actingAs($authenticatedUser)
            ->post(route('organizations.bulk-delete'), ['all' => true])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '2 organization(s) successfully deleted');

        expect(Organization::query()->count())->toBe(0);
    });
});
