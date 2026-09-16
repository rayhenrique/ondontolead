<?php

namespace Tests\Feature\Clinic;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\TriageRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AppointmentManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_access_appointments(): void
    {
        $this->get('/app/agendamentos')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_can_list_appointments_with_triage_information(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $appointment = Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Ana Beatriz Lima',
            'patient_phone' => '11999998888',
            'scheduled_at' => Carbon::parse('2026-10-20 14:30:00'),
            'status' => 'pending',
        ]);

        TriageRecord::factory()->for($appointment)->create([
            'pain_level' => 8,
            'urgency_level' => 'high',
            'suggested_procedure' => 'Restauração Estética',
            'ai_summary' => 'Paciente com dor aguda no dente molar inferior.',
            'raw_complaint' => 'Sinto muita dor ao mastigar alimentos frios.',
            'processed_by_ai' => true,
        ]);

        $this->actingAs($user)
            ->get('/app/agendamentos')
            ->assertOk()
            ->assertSee('Ana Beatriz Lima')
            ->assertSee('11999998888')
            ->assertSee('Alta')
            ->assertSee('Restauração Estética')
            ->assertSee('Paciente com dor aguda no dente molar inferior.');
    }

    public function test_filtering_appointments_by_status(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Paciente Pendente',
            'status' => 'pending',
        ]);

        Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Paciente Confirmado',
            'status' => 'confirmed',
        ]);

        $this->actingAs($user)
            ->get('/app/agendamentos?status=pending')
            ->assertOk()
            ->assertSee('Paciente Pendente')
            ->assertDontSee('Paciente Confirmado');
    }

    public function test_filtering_appointments_by_search_and_date(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Juliana Paes',
            'patient_phone' => '11987654321',
            'scheduled_at' => Carbon::parse('2026-11-15 10:00:00'),
        ]);

        Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Roberto Carlos',
            'patient_phone' => '21912345678',
            'scheduled_at' => Carbon::parse('2026-11-20 15:00:00'),
        ]);

        // Search by name
        $this->actingAs($user)
            ->get('/app/agendamentos?search=Juliana')
            ->assertOk()
            ->assertSee('Juliana Paes')
            ->assertDontSee('Roberto Carlos');

        // Search by phone
        $this->actingAs($user)
            ->get('/app/agendamentos?search=21912345678')
            ->assertOk()
            ->assertSee('Roberto Carlos')
            ->assertDontSee('Juliana Paes');

        // Filter by date
        $this->actingAs($user)
            ->get('/app/agendamentos?date=2026-11-15')
            ->assertOk()
            ->assertSee('Juliana Paes')
            ->assertDontSee('Roberto Carlos');
    }

    public function test_tenant_user_can_update_appointment_status(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $appointment = Appointment::factory()->for($clinic)->create([
            'patient_name' => 'Paciente Teste',
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch("/app/agendamentos/{$appointment->id}/status", [
                'status' => 'confirmed',
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_cannot_update_appointment_status_with_invalid_value(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $appointment = Appointment::factory()->for($clinic)->create([
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch("/app/agendamentos/{$appointment->id}/status", [
                'status' => 'status_inexistente',
            ])
            ->assertSessionHasErrors(['status']);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'pending',
        ]);
    }

    public function test_cross_tenant_protection_cannot_update_appointment_of_another_clinic(): void
    {
        $clinicA = Clinic::factory()->active()->create();
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create();
        $appointmentB = Appointment::factory()->for($clinicB)->create([
            'status' => 'pending',
        ]);

        $this->actingAs($userA)
            ->patch("/app/agendamentos/{$appointmentB->id}/status", [
                'status' => 'canceled',
            ])
            ->assertNotFound();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointmentB->id,
            'status' => 'pending',
        ]);
    }
}
