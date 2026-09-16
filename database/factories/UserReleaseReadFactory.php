<?php

namespace Database\Factories;

use App\Models\AppRelease;
use App\Models\User;
use App\Models\UserReleaseRead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserReleaseRead>
 */
class UserReleaseReadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'app_release_id' => AppRelease::factory(),
            'read_at' => now(),
        ];
    }
}
