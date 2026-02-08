<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class OwnProductFactory extends Factory
{
    protected $model = OwnProduct::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'unit_id' => 1, // Assuming at least one unit exists or will be created
            'category_id' => 1, // Assuming at least one category exists or will be created
            'price' => $this->faker->randomFloat(2, 10, 100),
            'investment' => $this->faker->randomFloat(2, 5, 50),
            'profit' => $this->faker->randomFloat(2, 2, 20),
        ];
    }
}
