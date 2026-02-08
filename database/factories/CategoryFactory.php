<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\Categories\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'name_other' => $this->faker->word(),
            'is_show' => true,
        ];
    }
}
