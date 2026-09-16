<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicBlockedDate>
 */
class ClinicBlockedDateFactory extends Factory
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
            'blocked_date' => fake()->dateTimeBetween('+1 day', '+1 year')->format('Y-m-d'),
            'reason' => fake()->optional()->sentence(4),
        ];
    }
}
