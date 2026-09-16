<?php

namespace Tests\Feature\Models\Scopes;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Scopes\TenantScope;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantScopeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_user_only_sees_appointments_from_its_clinic(): void
    {
        $clinicA = Clinic::factory()->create();
        $clinicB = Clinic::factory()->create();
        $appointmentA = Appointment::factory()->for($clinicA)->create([
            'scheduled_at' => '2026-10-01 09:00:00',
        ]);
        $appointmentB = Appointment::factory()->for($clinicB)->create([
            'scheduled_at' => '2026-10-01 10:00:00',
        ]);
        $user = User::factory()->tenant($clinicA)->create();

        $this->actingAs($user);

        $this->assertSame([$appointmentA->id], Appointment::query()->pluck('id')->all());
        $this->assertNull(Appointment::query()->find($appointmentB->id));
    }

    public function test_superadmin_sees_appointments_from_all_clinics(): void
    {
        Appointment::factory()->count(2)->create();
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin);

        $this->assertSame(2, Appointment::query()->count());
    }

    public function test_session_tenant_is_used_for_public_context_and_missing_context_is_closed(): void
    {
        $clinicA = Clinic::factory()->create();
        $clinicB = Clinic::factory()->create();
        $appointmentA = Appointment::factory()->for($clinicA)->create([
            'scheduled_at' => '2026-10-02 09:00:00',
        ]);
        Appointment::factory()->for($clinicB)->create([
            'scheduled_at' => '2026-10-02 10:00:00',
        ]);

        $this->assertSame(0, Appointment::query()->count());
        $this->assertSame(2, Appointment::withoutGlobalScope(TenantScope::class)->count());

        session(['tenant_id' => (string) $clinicA->id]);

        $this->assertSame([$appointmentA->id], Appointment::query()->pluck('id')->all());
    }

    public function test_tenant_creation_cannot_assign_another_clinic(): void
    {
        $clinicA = Clinic::factory()->create();
        $clinicB = Clinic::factory()->create();
        $user = User::factory()->tenant($clinicA)->create();
        $this->actingAs($user);

        $appointment = Appointment::query()->create([
            'clinic_id' => $clinicB->id,
            'patient_name' => 'Patient Name',
            'patient_phone' => '5585999999999',
            'scheduled_at' => '2026-10-03 09:00:00',
            'status' => 'pending',
        ]);

        $storedClinicId = DB::table('appointments')->where('id', $appointment->id)->value('clinic_id');

        $this->assertSame($clinicA->id, $storedClinicId);
    }
}
