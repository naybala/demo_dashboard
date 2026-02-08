<?php

namespace Database\Factories;

use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyIncomeFactory extends Factory
{
    protected $model = DailyIncome::class;

    public function definition(): array
    {
        return [
            'date' => now()->toDateString(),
            'name' => $this->faker->sentence(3),
            'amount' => $this->faker->numberBetween(1, 100),
            'own_product_id' => 1, // Will be overridden in tests
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'investment' => $this->faker->randomFloat(2, 5, 500),
            'profit' => $this->faker->randomFloat(2, 2, 200),
            'is_instant' => $this->faker->boolean(),
            'note' => $this->faker->sentence(),
            'created_by' => 1,
        ];
    }
}
