<?php

namespace Tests\Feature\Admin;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_superadmin_can_impersonate_clinic_and_access_tenant_area(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $clinic = Clinic::factory()->active()->create();
        $clinicUser = User::factory()->tenant($clinic)->create();

        $response = $this->actingAs($superAdmin, 'web')
            ->post("/admin/clinics/{$clinic->id}/impersonate");

        $response->assertRedirect('/app');
        $this->assertEquals($superAdmin->id, session('impersonator_id'));
        $this->assertAuthenticatedAs($clinicUser, 'web');
    }

    public function test_impersonating_clinic_without_user_creates_access_user_automatically(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $clinic = Clinic::factory()->active()->create();

        $this->assertEquals(0, $clinic->users()->count());

        $response = $this->actingAs($superAdmin, 'web')
            ->post("/admin/clinics/{$clinic->id}/impersonate");

        $response->assertRedirect('/app');
        $this->assertEquals(1, $clinic->users()->count());
        $this->assertEquals($superAdmin->id, session('impersonator_id'));
        $this->assertAuthenticated('web');
        $this->assertEquals($clinic->id, auth('web')->user()->clinic_id);
    }

    public function test_tenant_user_cannot_impersonate(): void
    {
        $tenantUser = User::factory()->tenant()->create();
        $anotherClinic = Clinic::factory()->active()->create();

        $this->actingAs($tenantUser, 'web')
            ->post("/admin/clinics/{$anotherClinic->id}/impersonate")
            ->assertForbidden();
    }

    public function test_user_can_leave_impersonation_and_restore_superadmin(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $clinic = Clinic::factory()->active()->create();
        $clinicUser = User::factory()->tenant($clinic)->create();

        // Start impersonation
        $this->actingAs($superAdmin, 'web')
            ->post("/admin/clinics/{$clinic->id}/impersonate");

        $this->assertAuthenticatedAs($clinicUser, 'web');

        // Leave impersonation
        $leaveResponse = $this->post('/admin/impersonate/leave');

        $leaveResponse->assertRedirect('/admin/clinics');
        $this->assertNull(session('impersonator_id'));
        $this->assertAuthenticatedAs($superAdmin, 'web');
        $this->assertTrue(auth('web')->user()->is_superadmin);
    }
}
