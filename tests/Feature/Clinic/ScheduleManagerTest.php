<?php

namespace Tests\Feature\Clinic;

use App\Livewire\Clinic\ScheduleManager;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ScheduleManagerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_access_schedule_page(): void
    {
        $this->get('/app/grade')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_can_access_schedule_page(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app/grade')
            ->assertOk()
            ->assertSeeLivewire(ScheduleManager::class);
    }

    public function test_schedule_manager_initializes_days_and_loads_existing_schedules(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(ScheduleManager::class)
            ->assertSet('schedules.1.is_active', true)
            ->assertSet('schedules.1.start_time', '09:00')
            ->assertSet('schedules.1.end_time', '17:00');
    }

    public function test_tenant_user_can_save_schedule(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user);

        Livewire::test(ScheduleManager::class)
            ->set('schedules.1.is_active', true)
            ->set('schedules.1.start_time', '08:30')
            ->set('schedules.1.end_time', '18:30')
            ->set('schedules.1.slot_duration_minutes', 45)
            ->call('saveSchedules')
            ->assertHasNoErrors()
            ->assertSet('feedbackMessage', 'Grade de horários da semana atualizada com sucesso!');

        $this->assertDatabaseHas('clinic_schedules', [
            'clinic_id' => $clinic->id,
            'day_of_week' => 1,
            'is_active' => true,
            'start_time' => '08:30',
            'end_time' => '18:30',
            'slot_duration_minutes' => 45,
        ]);
    }

    public function test_tenant_user_can_add_and_remove_blocked_date(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user);

        Livewire::test(ScheduleManager::class)
            ->set('newBlockedDate', '2026-12-25')
            ->set('newBlockedReason', 'Feriado de Natal')
            ->call('addBlockedDate')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('clinic_blocked_dates', [
            'clinic_id' => $clinic->id,
            'blocked_date' => '2026-12-25 00:00:00',
            'reason' => 'Feriado de Natal',
        ]);

        $blockedDate = ClinicBlockedDate::where('clinic_id', $clinic->id)->first();

        Livewire::test(ScheduleManager::class)
            ->call('removeBlockedDate', $blockedDate->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('clinic_blocked_dates', [
            'id' => $blockedDate->id,
        ]);
    }

    public function test_cannot_remove_blocked_date_of_another_clinic(): void
    {
        $clinicA = Clinic::factory()->active()->create();
        $userA = User::factory()->tenant($clinicA)->create();

        $clinicB = Clinic::factory()->active()->create();
        $blockedDateB = ClinicBlockedDate::factory()->for($clinicB)->create([
            'reason' => 'Recesso Clínica Beta',
        ]);

        $this->actingAs($userA);

        Livewire::test(ScheduleManager::class)
            ->call('removeBlockedDate', $blockedDateB->id);

        $this->assertDatabaseHas('clinic_blocked_dates', [
            'id' => $blockedDateB->id,
        ]);
    }
}
