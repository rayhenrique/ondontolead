<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppRelease;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Models\Plan;
use App\Models\TriageRecord;
use App\Models\User;
use App\Models\UserReleaseRead;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_clinic_domain_relationships_and_factory_states_are_connected(): void
    {
        $plan = Plan::factory()->create();
        $clinic = Clinic::factory()->for($plan)->active()->withOpenAi('openai-test-key')->create();
        $user = User::factory()->tenant($clinic)->create();
        $schedule = ClinicSchedule::factory()->for($clinic)->withBreak()->create([
            'day_of_week' => 1,
        ]);
        $blockedDate = ClinicBlockedDate::factory()->for($clinic)->create();
        $appointment = Appointment::factory()->for($clinic)->confirmed()->create();
        $triageRecord = TriageRecord::factory()
            ->for($appointment)
            ->highUrgency()
            ->processedByAi()
            ->create();
        $this->actingAs($user);

        $clinic->load(['plan', 'users', 'schedules', 'blockedDates', 'appointments']);
        $appointment->load('triageRecord');

        $this->assertTrue($plan->clinics->contains($clinic));
        $this->assertTrue($clinic->plan->is($plan));
        $this->assertTrue($clinic->users->contains($user));
        $this->assertTrue($clinic->schedules->contains($schedule));
        $this->assertTrue($clinic->blockedDates->contains($blockedDate));
        $this->assertTrue($clinic->appointments->contains($appointment));
        $this->assertTrue($appointment->clinic->is($clinic));
        $this->assertTrue($appointment->triageRecord->is($triageRecord));
        $this->assertTrue($triageRecord->appointment->is($appointment));
        $this->assertSame('active', $clinic->subscription_status);
        $this->assertSame('openai', $clinic->ai_provider);
        $this->assertSame('confirmed', $appointment->status);
        $this->assertSame('high', $triageRecord->urgency_level);
        $this->assertTrue($triageRecord->processed_by_ai);
    }

    public function test_release_read_relationships_are_connected(): void
    {
        $user = User::factory()->create();
        $release = AppRelease::factory()->withoutModal()->create();
        $releaseRead = UserReleaseRead::factory()
            ->for($user)
            ->for($release, 'appRelease')
            ->create();

        $this->assertTrue($user->releaseReads->contains($releaseRead));
        $this->assertTrue($release->userReleaseReads->contains($releaseRead));
        $this->assertTrue($releaseRead->user->is($user));
        $this->assertTrue($releaseRead->appRelease->is($release));
        $this->assertFalse($release->show_modal);
    }
}
