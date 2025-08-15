<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->optional()->paragraph(),
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
            'status' => $this->faker->randomElement(['new', 'in_progress', 'done']),
            'user_id' => 1
        ];
    }
}
