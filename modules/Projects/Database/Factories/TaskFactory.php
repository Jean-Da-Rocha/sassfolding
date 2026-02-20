<?php

declare(strict_types=1);

namespace Modules\Projects\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $status = fake()->randomElement(TaskStatus::cases());

        return [
            'completed_at' => $status === TaskStatus::Done ? fake()->dateTimeBetween('-30 days') : null,
            'description' => fake()->optional(0.6)->sentence(),
            'due_at' => fake()->optional(0.5)->dateTimeBetween('now', '+60 days'),
            'estimated_hours' => fake()->optional(0.4)->randomFloat(2, 0.5, 40),
            'is_pinned' => fake()->boolean(15),
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'project_id' => Project::factory(),
            'status' => $status,
            'title' => fake()->sentence(fake()->numberBetween(3, 8)),
        ];
    }
}
