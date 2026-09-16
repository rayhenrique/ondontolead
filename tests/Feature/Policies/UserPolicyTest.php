<?php

namespace Tests\Feature\Policies;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_manage_only_users_from_own_clinic(): void
    {
        $ownClinic = Clinic::factory()->create();
        $otherClinic = Clinic::factory()->create();
        $user = User::factory()->tenant($ownClinic)->create();
        $colleague = User::factory()->tenant($ownClinic)->create();
        $otherTenantUser = User::factory()->tenant($otherClinic)->create();
        $gate = Gate::forUser($user);

        $this->assertTrue($gate->allows('viewAny', User::class));
        $this->assertTrue($gate->allows('create', User::class));
        $this->assertTrue($gate->allows('view', $colleague));
        $this->assertTrue($gate->allows('update', $colleague));
        $this->assertTrue($gate->allows('delete', $colleague));
        $this->assertFalse($gate->allows('view', $otherTenantUser));
        $this->assertFalse($gate->allows('update', $otherTenantUser));
        $this->assertFalse($gate->allows('delete', $otherTenantUser));
    }

    public function test_superadmin_can_manage_users_from_any_clinic(): void
    {
        $tenantUser = User::factory()->tenant()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('view', $tenantUser));
        $this->assertTrue($gate->allows('update', $tenantUser));
        $this->assertTrue($gate->allows('delete', $tenantUser));
    }
}
