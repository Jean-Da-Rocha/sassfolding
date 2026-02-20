<?php

declare(strict_types=1);

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Enums\OrganizationRole;
use Modules\Organizations\Models\Organization;
use Modules\Users\Models\User;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        Organization::factory()
            ->count(20)
            ->create()
            ->each(function (Organization $organization) use ($users) {
                $members = $users->random(fake()->numberBetween(2, 5));

                $members->each(function (User $user, int $index) use ($organization) {
                    $organization->users()->attach($user, [
                        'role' => $index === 0
                            ? OrganizationRole::Admin->value
                            : fake()->randomElement([OrganizationRole::Member->value, OrganizationRole::Guest->value]),
                    ]);
                });
            });
    }
}
