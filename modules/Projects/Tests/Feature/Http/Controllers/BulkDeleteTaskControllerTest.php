<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Core\Enums\FlashMessage;
use Modules\Projects\Models\Task;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

describe('bulk delete tasks', function () {
    it('deletes selected tasks', function () {
        $authenticatedUser = User::factory()->create();
        $tasksToDelete = Task::factory()->count(3)->create();

        actingAs($authenticatedUser)
            ->post(route('tasks.bulk-delete'), [
                'only' => $tasksToDelete->pluck('id')->all(),
            ])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '3 task(s) successfully deleted');

        expect(Task::query()->count())->toBe(0);
    });

    it('deletes all tasks when "all" is true', function () {
        $authenticatedUser = User::factory()->create();
        Task::factory()->count(2)->create();

        actingAs($authenticatedUser)
            ->post(route('tasks.bulk-delete'), ['all' => true])
            ->assertRedirect()
            ->assertSessionHas(FlashMessage::Success->value, '2 task(s) successfully deleted');

        expect(Task::query()->count())->toBe(0);
    });
});
