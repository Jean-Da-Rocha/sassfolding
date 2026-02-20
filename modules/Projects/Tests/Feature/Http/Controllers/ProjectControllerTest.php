<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Organizations\Models\Organization;
use Modules\Projects\Enums\ProjectStatus;
use Modules\Projects\Models\Project;
use Modules\Users\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

describe('projects listing', function () {
    it('lists all of the projects', function () {
        $authenticatedUser = User::factory()->create();
        $organization = Organization::factory()->create();

        Project::factory()->count(5)->create([
            'organization_id' => $organization->id,
            'owner_id' => $authenticatedUser->id,
        ]);

        actingAs($authenticatedUser)
            ->get(route('projects.index'))
            ->assertOk()
            ->assertHybridView('projects::list-projects')
            ->assertHybridProperties(['projects.records' => 5]);
    });

    it('shows the view for creating a new project', function () {
        $authenticatedUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->get(route('projects.create'))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('projects::list-projects')
            ->assertHybridDialog(view: 'projects::create-project');
    });

    it('stores a new project and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $organization = Organization::factory()->create();

        actingAs($authenticatedUser)
            ->post(route('projects.store'), [
                'description' => 'A test project',
                'name' => 'Test Project',
                'organization_id' => $organization->id,
                'status' => ProjectStatus::Active->value,
            ])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Project created successfully.');

        assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'owner_id' => $authenticatedUser->id,
        ]);
    });

    it('shows the view for editing an existing project', function () {
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $authenticatedUser->id]);

        actingAs($authenticatedUser)
            ->get(route('projects.edit', $project))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('projects::list-projects')
            ->assertHybridDialog(
                properties: [
                    'project.name' => $project->name,
                ],
                view: 'projects::edit-project',
            );
    });

    it('updates an existing project and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create(['name' => 'Old Name', 'owner_id' => $authenticatedUser->id]);

        actingAs($authenticatedUser)
            ->put(route('projects.update', $project), [
                'description' => $project->description,
                'name' => 'New Name',
                'organization_id' => $project->organization_id,
                'status' => $project->status->value,
            ])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Project updated successfully.');

        assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'New Name']);
    });

    it('soft-deletes an existing project and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $authenticatedUser->id]);

        actingAs($authenticatedUser)
            ->delete(route('projects.destroy', $project))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Project deleted successfully.');

        expect(Project::query()->where('id', $project->id)->exists())->toBeFalse()
            ->and(Project::withTrashed()->where('id', $project->id)->exists())->toBeTrue();
    });
});
