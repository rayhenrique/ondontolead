<?php

namespace Tests\Feature\Policies;

use App\Models\Clinic;
use App\Models\ClinicSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ClinicSchedulePolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_manage_only_own_schedule(): void
    {
        $ownClinic = Clinic::factory()->create();
        $otherClinic = Clinic::factory()->create();
        $ownSchedule = ClinicSchedule::factory()->for($ownClinic)->create();
        $otherSchedule = ClinicSchedule::factory()->for($otherClinic)->create();
        $gate = Gate::forUser(User::factory()->tenant($ownClinic)->create());

        $this->assertTrue($gate->allows('viewAny', ClinicSchedule::class));
        $this->assertTrue($gate->allows('create', ClinicSchedule::class));
        $this->assertTrue($gate->allows('view', $ownSchedule));
        $this->assertTrue($gate->allows('update', $ownSchedule));
        $this->assertTrue($gate->allows('delete', $ownSchedule));
        $this->assertFalse($gate->allows('view', $otherSchedule));
        $this->assertFalse($gate->allows('update', $otherSchedule));
        $this->assertFalse($gate->allows('delete', $otherSchedule));
    }

    public function test_superadmin_bypasses_schedule_tenant_boundary(): void
    {
        $schedule = ClinicSchedule::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('view', $schedule));
        $this->assertTrue($gate->allows('update', $schedule));
        $this->assertTrue($gate->allows('delete', $schedule));
    }
}
