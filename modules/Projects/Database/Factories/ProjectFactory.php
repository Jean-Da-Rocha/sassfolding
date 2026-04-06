<?php

declare(strict_types=1);

namespace Modules\Projects\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\Organization;
use Modules\Projects\Enums\ProjectStatus;
use Modules\Projects\Models\Project;
use Modules\Users\Models\User;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'description' => fake()->optional(0.7)->paragraph(),
            'name' => fake()->company().' '.fake()->word(),
            'organization_id' => Organization::factory(),
            'owner_id' => User::factory(),
            'status' => fake()->randomElement(ProjectStatus::cases()),
        ];
    }

    public function archived(): static
    {
        return $this->state(fn () => ['status' => ProjectStatus::Archived]);
    }
}
