<?php

namespace Tests\Feature\Policies;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\TriageRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class TriageRecordPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_manage_only_triage_from_own_appointments(): void
    {
        $ownClinic = Clinic::factory()->create();
        $otherClinic = Clinic::factory()->create();
        $ownAppointment = Appointment::factory()->for($ownClinic)->create();
        $otherAppointment = Appointment::factory()->for($otherClinic)->create();
        $ownTriage = TriageRecord::factory()->for($ownAppointment)->create();
        $otherTriage = TriageRecord::factory()->for($otherAppointment)->create();
        $gate = Gate::forUser(User::factory()->tenant($ownClinic)->create());

        $this->assertTrue($gate->allows('viewAny', TriageRecord::class));
        $this->assertTrue($gate->allows('create', TriageRecord::class));
        $this->assertTrue($gate->allows('view', $ownTriage));
        $this->assertTrue($gate->allows('update', $ownTriage));
        $this->assertTrue($gate->allows('delete', $ownTriage));
        $this->assertFalse($gate->allows('view', $otherTriage));
        $this->assertFalse($gate->allows('update', $otherTriage));
        $this->assertFalse($gate->allows('delete', $otherTriage));
    }

    public function test_superadmin_bypasses_triage_tenant_boundary(): void
    {
        $triage = TriageRecord::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('view', $triage));
        $this->assertTrue($gate->allows('update', $triage));
        $this->assertTrue($gate->allows('delete', $triage));
    }
}
