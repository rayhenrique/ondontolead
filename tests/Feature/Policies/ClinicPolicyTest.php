<?php

namespace Tests\Feature\Policies;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ClinicPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_view_and_update_only_own_clinic(): void
    {
        $ownClinic = Clinic::factory()->create();
        $otherClinic = Clinic::factory()->create();
        $user = User::factory()->tenant($ownClinic)->create();
        $gate = Gate::forUser($user);

        $this->assertTrue($gate->allows('view', $ownClinic));
        $this->assertTrue($gate->allows('update', $ownClinic));
        $this->assertFalse($gate->allows('view', $otherClinic));
        $this->assertFalse($gate->allows('update', $otherClinic));
        $this->assertFalse($gate->allows('viewAny', Clinic::class));
        $this->assertFalse($gate->allows('create', Clinic::class));
        $this->assertFalse($gate->allows('delete', $ownClinic));
    }

    public function test_superadmin_can_manage_any_clinic(): void
    {
        $clinic = Clinic::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('viewAny', Clinic::class));
        $this->assertTrue($gate->allows('view', $clinic));
        $this->assertTrue($gate->allows('create', Clinic::class));
        $this->assertTrue($gate->allows('update', $clinic));
        $this->assertTrue($gate->allows('delete', $clinic));
    }
}
