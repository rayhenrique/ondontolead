<?php

namespace Tests\Feature\Policies;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AppointmentPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_manage_only_own_appointments(): void
    {
        $ownClinic = Clinic::factory()->create();
        $otherClinic = Clinic::factory()->create();
        $ownAppointment = Appointment::factory()->for($ownClinic)->create();
        $otherAppointment = Appointment::factory()->for($otherClinic)->create();
        $gate = Gate::forUser(User::factory()->tenant($ownClinic)->create());

        $this->assertTrue($gate->allows('viewAny', Appointment::class));
        $this->assertTrue($gate->allows('create', Appointment::class));
        $this->assertTrue($gate->allows('view', $ownAppointment));
        $this->assertTrue($gate->allows('update', $ownAppointment));
        $this->assertTrue($gate->allows('delete', $ownAppointment));
        $this->assertFalse($gate->allows('view', $otherAppointment));
        $this->assertFalse($gate->allows('update', $otherAppointment));
        $this->assertFalse($gate->allows('delete', $otherAppointment));
        $this->assertFalse($gate->allows('restore', $ownAppointment));
        $this->assertFalse($gate->allows('forceDelete', $ownAppointment));
    }

    public function test_superadmin_bypasses_appointment_tenant_boundary(): void
    {
        $appointment = Appointment::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('view', $appointment));
        $this->assertTrue($gate->allows('update', $appointment));
        $this->assertTrue($gate->allows('delete', $appointment));
    }
}
