<?php

namespace Tests\Feature;

use App\Livewire\Clinic\ScheduleManager;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Models\TriageRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TenantDatabaseIsolationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_a_cannot_read_tenant_b_appointments_and_triages(): void
    {
        $clinicA = Clinic::factory()->active()->create(['name' => 'Clínica Alfa']);
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create(['name' => 'Clínica Beta']);
        $userB = User::factory()->tenant($clinicB)->create();

        $appointmentA = Appointment::factory()->for($clinicA)->create([
            'patient_name' => 'Paciente Exclusivo Alfa',
            'scheduled_at' => now()->addDay(),
        ]);
        TriageRecord::factory()->for($appointmentA)->create([
            'ai_summary' => 'Resumo Clínico da Alfa',
        ]);

        $appointmentB = Appointment::factory()->for($clinicB)->create([
            'patient_name' => 'Paciente Exclusivo Beta',
            'scheduled_at' => now()->addDay(),
        ]);
        TriageRecord::factory()->for($appointmentB)->create([
            'ai_summary' => 'Resumo Clínico da Beta',
        ]);

        // Autenticado como Usuário da Clínica Alfa
        $this->actingAs($userA);

        // Dashboard da clínica
        $this->get('/app')
            ->assertOk()
            ->assertSee('Paciente Exclusivo Alfa')
            ->assertDontSee('Paciente Exclusivo Beta');

        // Listagem de agendamentos
        $this->get('/app/agendamentos')
            ->assertOk()
            ->assertSee('Paciente Exclusivo Alfa')
            ->assertSee('Resumo Clínico da Alfa')
            ->assertDontSee('Paciente Exclusivo Beta')
            ->assertDontSee('Resumo Clínico da Beta');

        // Consultas diretas via Eloquent escopado pelo TenantScope
        $appointments = Appointment::all();
        $this->assertTrue($appointments->contains($appointmentA));
        $this->assertFalse($appointments->contains($appointmentB));
        $this->assertSame(1, Appointment::count());
    }

    public function test_tenant_a_cannot_mutate_tenant_b_appointment(): void
    {
        $clinicA = Clinic::factory()->active()->create();
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create();
        $appointmentB = Appointment::factory()->for($clinicB)->create([
            'status' => 'pending',
        ]);

        $this->actingAs($userA);

        // Tentativa de alterar status do agendamento da clínica B retorna 404 (invisível ao tenant A)
        $this->patch("/app/agendamentos/{$appointmentB->id}/status", [
            'status' => 'confirmed',
        ])->assertNotFound();

        // O status no banco permanece inalterado
        $this->assertSame('pending', $appointmentB->fresh()->status);
    }

    public function test_tenant_a_cannot_view_or_modify_tenant_b_schedules_and_blocked_dates(): void
    {
        $clinicA = Clinic::factory()->active()->create();
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create();
        $blockedDateB = ClinicBlockedDate::factory()->for($clinicB)->create([
            'blocked_date' => '2026-12-25',
            'reason' => 'Feriado de Natal Beta',
        ]);

        $this->actingAs($userA);

        // Livewire ScheduleManager
        $component = Livewire::test(ScheduleManager::class);

        // Usuário A não vê as datas bloqueadas da Clínica B
        $blockedDates = $component->get('blockedDates');
        $this->assertEmpty($blockedDates);

        // Tentativa de remoção de data bloqueada da Clínica B não surte efeito
        $component->call('removeBlockedDate', $blockedDateB->id);
        $this->assertDatabaseHas('clinic_blocked_dates', [
            'id' => $blockedDateB->id,
            'reason' => 'Feriado de Natal Beta',
        ]);

        // Salvar horários do Tenant A não afeta Clinic B
        $component->set('schedules.1.is_active', true)
            ->set('schedules.1.start_time', '08:00')
            ->set('schedules.1.end_time', '18:00')
            ->set('schedules.1.slot_duration_minutes', 30)
            ->call('saveSchedules');

        $this->assertDatabaseHas('clinic_schedules', [
            'clinic_id' => $clinicA->id,
            'day_of_week' => 1,
        ]);
        $this->assertDatabaseMissing('clinic_schedules', [
            'clinic_id' => $clinicB->id,
            'day_of_week' => 1,
        ]);
    }

    public function test_tenant_a_cannot_read_or_overwrite_tenant_b_settings_and_byok_keys(): void
    {
        $clinicA = Clinic::factory()->active()->create([
            'name' => 'Clínica A',
            'slug' => 'clinica-a',
            'ai_provider' => 'none',
        ]);
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create([
            'name' => 'Clínica B Original',
            'slug' => 'clinica-b',
            'ai_provider' => 'openai',
            'ai_api_key' => 'secret-openai-key-b',
        ]);

        $this->actingAs($userA);

        // Tela de configurações exibe apenas os dados de A
        $this->get('/app/configuracoes')
            ->assertOk()
            ->assertSee('Clínica A')
            ->assertDontSee('Clínica B Original');

        // Atualizar configurações afeta apenas A
        $this->put('/app/configuracoes', [
            'name' => 'Clínica A Renovada',
            'slug' => 'clinica-a-nova',
            'whatsapp_number' => '11988887777',
            'ai_provider' => 'gemini',
            'ai_api_key' => 'gemini-key-a-12345',
        ])->assertRedirect();

        $this->assertSame('Clínica A Renovada', $clinicA->fresh()->name);
        $this->assertSame('gemini-key-a-12345', $clinicA->fresh()->ai_api_key);

        // Clínica B permanece totalmente intacta
        $this->assertSame('Clínica B Original', $clinicB->fresh()->name);
        $this->assertSame('secret-openai-key-b', $clinicB->fresh()->ai_api_key);
    }

    public function test_fail_closed_behavior_when_unauthenticated(): void
    {
        $clinic = Clinic::factory()->active()->create();
        Appointment::factory()->for($clinic)->create();
        ClinicSchedule::factory()->for($clinic)->create();
        ClinicBlockedDate::factory()->for($clinic)->create();

        // Sem usuário autenticado (fail-closed)
        $this->assertSame(0, Appointment::count());
        $this->assertSame(0, ClinicSchedule::count());
        $this->assertSame(0, ClinicBlockedDate::count());

        // Com withoutGlobalScopes, todos os dados estão lá
        $this->assertSame(1, Appointment::withoutGlobalScopes()->count());
        $this->assertSame(1, ClinicSchedule::withoutGlobalScopes()->count());
        $this->assertSame(1, ClinicBlockedDate::withoutGlobalScopes()->count());
    }

    public function test_superadmin_bypasses_tenant_isolation_for_system_governance(): void
    {
        $clinicA = Clinic::factory()->active()->create();
        $clinicB = Clinic::factory()->active()->create();

        Appointment::factory()->for($clinicA)->create();
        Appointment::factory()->for($clinicB)->create();

        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin);

        // SuperAdmin vê registros de todas as clínicas sem filtro de isolamento
        $this->assertSame(2, Appointment::count());
    }
}
