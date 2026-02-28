<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'promoter_id' => \App\Models\Promoter::factory(),
            'wallet_balance' => $this->faker->randomFloat(5, 0, 40),
            'investment_goal' => $this->faker->randomFloat(5, 100, 4000),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}
