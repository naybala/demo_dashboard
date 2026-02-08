<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\Roles\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'guard_name' => 'web',
            'can_access_panel' => true,
        ];
    }
}
