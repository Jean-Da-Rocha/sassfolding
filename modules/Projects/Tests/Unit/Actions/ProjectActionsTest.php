<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Enums\FlashMessage;
use Modules\Projects\Actions\DeleteProjectAction;
use Modules\Projects\Actions\DeleteTaskAction;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;

uses(RefreshDatabase::class);

describe('delete project action', function () {
    it('soft-deletes the project from the database', function () {
        $project = Project::factory()->create();

        app(DeleteProjectAction::class)->execute($project);

        expect(Project::query()->where('id', $project->id)->exists())->toBeFalse()
            ->and(Project::withTrashed()->where('id', $project->id)->exists())->toBeTrue();
    });

    it('flashes a success message', function () {
        $project = Project::factory()->create();

        app(DeleteProjectAction::class)->execute($project);

        expect(session()->get(FlashMessage::Success->value))->toBe('Project deleted successfully.');
    });
});

describe('delete task action', function () {
    it('deletes the task from the database', function () {
        $task = Task::factory()->create();

        app(DeleteTaskAction::class)->execute($task);

        expect(Task::query()->where('id', $task->id)->exists())->toBeFalse();
    });

    it('flashes a success message', function () {
        $task = Task::factory()->create();

        app(DeleteTaskAction::class)->execute($task);

        expect(session()->get(FlashMessage::Success->value))->toBe('Task deleted successfully.');
    });
});
