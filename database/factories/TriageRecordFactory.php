<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\TriageRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TriageRecord>
 */
class TriageRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'raw_complaint' => fake()->sentence(),
            'pain_level' => fake()->numberBetween(0, 10),
            'urgency_level' => 'low',
            'suggested_procedure' => null,
            'ai_summary' => null,
            'processed_by_ai' => false,
        ];
    }

    public function highUrgency(): static
    {
        return $this->state(fn (array $attributes): array => [
            'pain_level' => 10,
            'urgency_level' => 'high',
        ]);
    }

    public function processedByAi(): static
    {
        return $this->state(fn (array $attributes): array => [
            'suggested_procedure' => 'Avaliação odontológica',
            'ai_summary' => 'Triagem processada por IA para priorização clínica.',
            'processed_by_ai' => true,
        ]);
    }
}
