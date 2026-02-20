<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Projects\Models\Project;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

describe('bulk delete projects', function () {
    it('deletes selected projects', function () {
        $authenticatedUser = User::factory()->create();
        $projectsToDelete = Project::factory()->count(3)->create(['owner_id' => $authenticatedUser->id]);

        actingAs($authenticatedUser)
            ->post(route('projects.bulk-delete'), [
                'only' => $projectsToDelete->pluck('id')->all(),
            ])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '3 project(s) successfully deleted');

        expect(Project::query()->count())->toBe(0);
    });

    it('deletes all projects when "all" is true', function () {
        $authenticatedUser = User::factory()->create();
        Project::factory()->count(2)->create(['owner_id' => $authenticatedUser->id]);

        actingAs($authenticatedUser)
            ->post(route('projects.bulk-delete'), ['all' => true])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '2 project(s) successfully deleted');

        expect(Project::query()->count())->toBe(0);
    });
});
