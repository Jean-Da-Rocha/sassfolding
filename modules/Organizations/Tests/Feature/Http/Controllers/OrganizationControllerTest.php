<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Organizations\Models\Organization;
use Modules\Users\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

describe('organizations listing', function () {
    it('lists all of the organizations', function () {
        $authenticatedUser = User::factory()->create();

        Organization::factory()->count(5)->create();

        actingAs($authenticatedUser)
            ->get(route('organizations.index'))
            ->assertOk()
            ->assertHybridView('organizations::list-organizations')
            ->assertHybridProperties(['organizations.records' => 5]);
    });

    it('shows the view for creating a new organization', function () {
        $authenticatedUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->get(route('organizations.create'))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('organizations::list-organizations')
            ->assertHybridDialog(view: 'organizations::create-organization');
    });

    it('stores a new organization and returns a success message', function () {
        $authenticatedUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->post(route('organizations.store'), [
                'name' => 'Acme Corp',
                'slug' => 'acme-corp',
            ])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Organization created successfully.');

        assertDatabaseHas('organizations', ['name' => 'Acme Corp', 'slug' => 'acme-corp']);
    });

    it('shows the view for editing an existing organization', function () {
        $authenticatedUser = User::factory()->create();
        $organization = Organization::factory()->create();

        actingAs($authenticatedUser)
            ->get(route('organizations.edit', $organization))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('organizations::list-organizations')
            ->assertHybridDialog(
                properties: [
                    'organization.name' => $organization->name,
                    'organization.slug' => $organization->slug,
                ],
                view: 'organizations::edit-organization',
            );
    });

    it('updates an existing organization and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $organization = Organization::factory()->create(['name' => 'Old Name']);

        actingAs($authenticatedUser)
            ->put(route('organizations.update', $organization), [
                'name' => 'New Name',
                'slug' => $organization->slug,
            ])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Organization updated successfully.');

        assertDatabaseHas('organizations', ['id' => $organization->id, 'name' => 'New Name']);
    });

    it('soft-deletes an existing organization and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $organization = Organization::factory()->create();

        actingAs($authenticatedUser)
            ->delete(route('organizations.destroy', $organization))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Organization deleted successfully.');

        expect(Organization::query()->where('id', $organization->id)->exists())->toBeFalse()
            ->and(Organization::withTrashed()->where('id', $organization->id)->exists())->toBeTrue();
    });
});
