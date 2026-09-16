<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicSchedule>
 */
class ClinicScheduleFactory extends Factory
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
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'break_start' => null,
            'break_end' => null,
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function withBreak(): static
    {
        return $this->state(fn (array $attributes): array => [
            'break_start' => '12:00:00',
            'break_end' => '13:00:00',
        ]);
    }
}
