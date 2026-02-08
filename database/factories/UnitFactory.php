<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\Units\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
