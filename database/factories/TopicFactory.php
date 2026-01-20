<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'board_id' => \App\Models\Board::factory(),
            'user_id' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->optional(0.6)->paragraph(),
            'slug' => fake()->unique()->slug(),
            'status' => \App\Enums\TopicStatus::Normal->value,
            'is_pinned' => fake()->boolean(10),
            'is_locked' => fake()->boolean(5),
            'view_count' => fake()->numberBetween(0, 10000),
        ];
    }

    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
            'status' => \App\Enums\TopicStatus::Pinned->value,
        ]);
    }

    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_locked' => true,
            'status' => \App\Enums\TopicStatus::Locked->value,
        ]);
    }
}
