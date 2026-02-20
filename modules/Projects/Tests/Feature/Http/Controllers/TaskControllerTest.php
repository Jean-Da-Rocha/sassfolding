<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;
use Modules\Users\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

describe('tasks listing', function () {
    it('lists all of the tasks', function () {
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $authenticatedUser->id]);

        Task::factory()->count(5)->create(['project_id' => $project->id]);

        actingAs($authenticatedUser)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertHybridView('projects::list-tasks')
            ->assertHybridProperties(['tasks.records' => 5]);
    });

    it('shows the view for creating a new task', function () {
        $authenticatedUser = User::factory()->create();

        actingAs($authenticatedUser)
            ->get(route('tasks.create'))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('projects::list-tasks')
            ->assertHybridDialog(view: 'projects::create-task');
    });

    it('stores a new task and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $authenticatedUser->id]);

        actingAs($authenticatedUser)
            ->post(route('tasks.store'), [
                'priority' => TaskPriority::Medium->value,
                'project_id' => $project->id,
                'status' => TaskStatus::Todo->value,
                'title' => 'Test Task',
            ])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Task created successfully.');

        assertDatabaseHas('tasks', ['title' => 'Test Task', 'project_id' => $project->id]);
    });

    it('shows the view for editing an existing task', function () {
        $authenticatedUser = User::factory()->create();
        $task = Task::factory()->create();

        actingAs($authenticatedUser)
            ->get(route('tasks.edit', $task))
            ->assertOk()
            ->assertHybrid()
            ->assertHybridView('projects::list-tasks')
            ->assertHybridDialog(
                properties: [
                    'task.title' => $task->title,
                ],
                view: 'projects::edit-task',
            );
    });

    it('updates an existing task and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $task = Task::factory()->create(['title' => 'Old Title']);

        actingAs($authenticatedUser)
            ->put(route('tasks.update', $task), [
                'priority' => $task->priority->value,
                'project_id' => $task->project_id,
                'status' => $task->status->value,
                'title' => 'New Title',
            ])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Task updated successfully.');

        assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'New Title']);
    });

    it('deletes an existing task and returns a success message', function () {
        $authenticatedUser = User::factory()->create();
        $task = Task::factory()->create();

        actingAs($authenticatedUser)
            ->delete(route('tasks.destroy', $task))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas(FlashMessage::Success->value, 'Task deleted successfully.');

        assertDatabaseMissing('tasks', ['id' => $task->id]);
    });
});
