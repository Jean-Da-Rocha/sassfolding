<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Enums\FlashMessage;
use Modules\Organizations\Actions\DeleteOrganizationAction;
use Modules\Organizations\Models\Organization;

uses(RefreshDatabase::class);

describe('delete organization action', function () {
    it('soft-deletes the organization from the database', function () {
        $organization = Organization::factory()->create();

        app(DeleteOrganizationAction::class)->execute($organization);

        expect(Organization::query()->where('id', $organization->id)->exists())->toBeFalse()
            ->and(Organization::withTrashed()->where('id', $organization->id)->exists())->toBeTrue();
    });

    it('flashes a success message', function () {
        $organization = Organization::factory()->create();

        app(DeleteOrganizationAction::class)->execute($organization);

        expect(session()->get(FlashMessage::Success->value))->toBe('Organization deleted successfully.');
    });
});
