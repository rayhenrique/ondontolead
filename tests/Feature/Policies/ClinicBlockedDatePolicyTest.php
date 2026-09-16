<?php

namespace Tests\Feature\Policies;

use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ClinicBlockedDatePolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_manage_only_own_blocked_dates(): void
    {
        $ownClinic = Clinic::factory()->create();
        $otherClinic = Clinic::factory()->create();
        $ownBlockedDate = ClinicBlockedDate::factory()->for($ownClinic)->create();
        $otherBlockedDate = ClinicBlockedDate::factory()->for($otherClinic)->create();
        $gate = Gate::forUser(User::factory()->tenant($ownClinic)->create());

        $this->assertTrue($gate->allows('viewAny', ClinicBlockedDate::class));
        $this->assertTrue($gate->allows('create', ClinicBlockedDate::class));
        $this->assertTrue($gate->allows('view', $ownBlockedDate));
        $this->assertTrue($gate->allows('update', $ownBlockedDate));
        $this->assertTrue($gate->allows('delete', $ownBlockedDate));
        $this->assertFalse($gate->allows('view', $otherBlockedDate));
        $this->assertFalse($gate->allows('update', $otherBlockedDate));
        $this->assertFalse($gate->allows('delete', $otherBlockedDate));
    }

    public function test_superadmin_bypasses_blocked_date_tenant_boundary(): void
    {
        $blockedDate = ClinicBlockedDate::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('view', $blockedDate));
        $this->assertTrue($gate->allows('update', $blockedDate));
        $this->assertTrue($gate->allows('delete', $blockedDate));
    }
}
