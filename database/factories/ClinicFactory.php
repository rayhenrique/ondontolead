<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Clinic>
 */
class ClinicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(3),
            'whatsapp_number' => '55'.fake()->numerify('###########'),
            'ai_provider' => 'none',
            'ai_api_key' => null,
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'mp_subscription_id' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'subscription_status' => 'active',
            'trial_ends_at' => null,
        ]);
    }

    public function trial(): static
    {
        return $this->state(fn (array $attributes): array => [
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ]);
    }

    public function pastDue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'subscription_status' => 'past_due',
        ]);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'subscription_status' => 'canceled',
        ]);
    }

    public function withOpenAi(string $apiKey = 'test-openai-key'): static
    {
        return $this->state(fn (array $attributes): array => [
            'ai_provider' => 'openai',
            'ai_api_key' => $apiKey,
        ]);
    }

    public function withGemini(string $apiKey = 'test-gemini-key'): static
    {
        return $this->state(fn (array $attributes): array => [
            'ai_provider' => 'gemini',
            'ai_api_key' => $apiKey,
        ]);
    }
}
