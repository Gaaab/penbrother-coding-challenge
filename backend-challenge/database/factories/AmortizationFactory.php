<?php

namespace Database\Factories;

use App\Enums\AmortizationState;
use App\Models\Amortization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AmortizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Amortization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $state = $this->faker->randomElement([AmortizationState::PENDING->value, AmortizationState::PAID->value]);

        return [
            'amount' => $this->faker->numberBetween(10000, 500000),
            'state' => $state,
            'schedule_date' => $this->faker->dateTimeBetween('-2 years', '+1 year')->format('Y-m-d'),
        ];
    }

    public function pending(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'state' => AmortizationState::PENDING->value,
        ]);
    }

    public function paid(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'state' => AmortizationState::PAID->value,
        ]);
    }
}
