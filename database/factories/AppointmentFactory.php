<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'patient_name' => fake()->name(),
            'patient_phone' => '55'.fake()->numerify('###########'),
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'status' => 'pending',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'confirmed']);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'completed']);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'canceled']);
    }

    public function noShow(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => 'no_show']);
    }
}
