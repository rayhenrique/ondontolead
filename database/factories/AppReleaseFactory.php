<?php

namespace Database\Factories;

use App\Models\AppRelease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AppRelease>
 */
class AppReleaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'version' => 'v0.'.fake()->unique()->numberBetween(1, 999).'.0',
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(),
            'show_modal' => true,
            'released_at' => now(),
        ];
    }

    public function withoutModal(): static
    {
        return $this->state(fn (array $attributes): array => [
            'show_modal' => false,
        ]);
    }
}
