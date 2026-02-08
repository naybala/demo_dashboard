<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\Products\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'name_other' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 10000),
            'photos' => [$this->faker->imageUrl()],
            'description' => $this->faker->sentence(),
            'description_other' => $this->faker->sentence(),
        ];
    }
}
