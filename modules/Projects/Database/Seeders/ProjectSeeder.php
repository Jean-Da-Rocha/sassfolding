<?php

declare(strict_types=1);

namespace Modules\Projects\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Models\Organization;
use Modules\Projects\Models\Project;
use Modules\Projects\Models\Task;
use Modules\Users\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $organizations = Organization::all();

        Project::factory()
            ->count(15)
            ->sequence(fn () => [
                'organization_id' => $organizations->random()->id,
                'owner_id' => $users->random()->id,
            ])
            ->create()
            ->each(fn (Project $project) => Task::factory()
                ->count(fake()->numberBetween(10, 20))
                ->for($project)
                ->create()
            );
    }
}
