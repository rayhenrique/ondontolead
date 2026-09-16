<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Models\TriageRecord;
use App\Models\User;
use Database\Seeders\DemoClinicSeeder;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DemoClinicSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeds_demo_clinic_with_complete_fictitious_data(): void
    {
        $this->seed(PlanSeeder::class);

        $this->seed(DemoClinicSeeder::class);
        // Test idempotency
        $this->seed(DemoClinicSeeder::class);

        // Verify Clinic
        $clinic = Clinic::query()->where('slug', 'odontovida')->first();
        $this->assertNotNull($clinic);
        $this->assertSame('OdontoVida Odontologia Integrada', $clinic->name);
        $this->assertSame('trial', $clinic->subscription_status);
        $this->assertSame('gemini', $clinic->ai_provider);

        // Verify Clinic User
        $user = User::query()->where('email', 'dra.camila@odontovida.com.br')->first();
        $this->assertNotNull($user);
        $this->assertSame($clinic->id, $user->clinic_id);
        $this->assertTrue(Hash::check('password', $user->password));

        // Verify Schedules (7 days)
        $schedulesCount = ClinicSchedule::withoutGlobalScopes()->where('clinic_id', $clinic->id)->count();
        $this->assertSame(7, $schedulesCount);

        // Verify Blocked Dates
        $blockedCount = ClinicBlockedDate::withoutGlobalScopes()->where('clinic_id', $clinic->id)->count();
        $this->assertGreaterThanOrEqual(2, $blockedCount);

        // Verify Appointments & Triages
        $appointmentsCount = Appointment::withoutGlobalScopes()->where('clinic_id', $clinic->id)->count();
        $this->assertGreaterThanOrEqual(10, $appointmentsCount);

        $triagesCount = TriageRecord::query()->whereIn(
            'appointment_id',
            Appointment::withoutGlobalScopes()->where('clinic_id', $clinic->id)->pluck('id')
        )->count();
        $this->assertGreaterThanOrEqual(10, $triagesCount);

        // Verify Public Landing Page is Accessible
        $response = $this->get('/odontovida');
        $response->assertOk();
        $response->assertSee('OdontoVida Odontologia Integrada');
    }
}
