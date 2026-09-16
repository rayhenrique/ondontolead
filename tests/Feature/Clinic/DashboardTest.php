<?php

namespace Tests\Feature\Clinic;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_to_login_from_app_dashboard(): void
    {
        $this->get('/app')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_can_view_clinic_dashboard_with_kpis(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'name' => 'Clínica Sorriso Perfeito',
            'slug' => 'sorriso-perfeito',
            'whatsapp_number' => '11988887777',
        ]);
        $user = User::factory()->tenant($clinic)->create();

        // Appointments
        $now = Carbon::now();
        Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Carlos Silva',
            'scheduled_at' => $now->copy()->addHours(2),
            'status' => 'confirmed',
        ]);

        Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Maria Oliveira',
            'scheduled_at' => $now->copy()->addDays(2),
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get('/app')
            ->assertOk()
            ->assertSee('Clínica Sorriso Perfeito')
            ->assertSee('Carlos Silva')
            ->assertSee('sorriso-perfeito')
            ->assertSee('11988887777');
    }

    public function test_onboarding_alert_shown_when_no_active_schedule_exists(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertOk()
            ->assertSee('Grade de atendimento não configurada');
    }

    public function test_onboarding_alert_hidden_when_active_schedule_exists(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get('/app')
            ->assertOk()
            ->assertDontSee('Grade de atendimento não configurada');
    }

    public function test_dashboard_isolates_data_between_clinics(): void
    {
        $clinicA = Clinic::factory()->active()->create(['name' => 'Clínica Alfa']);
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create(['name' => 'Clínica Beta']);
        Appointment::factory()->for($clinicB)->create([
            'patient_name' => 'Paciente Exclusivo de Beta',
            'scheduled_at' => now()->addHour(),
        ]);

        $this->actingAs($userA)
            ->get('/app')
            ->assertOk()
            ->assertDontSee('Paciente Exclusivo de Beta');
    }
}
