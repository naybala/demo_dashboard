<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\Permissions\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'guard_name' => 'web',
        ];
    }
}
