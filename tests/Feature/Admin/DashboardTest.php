<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_is_forbidden_from_admin_dashboard(): void
    {
        $tenantUser = User::factory()->tenant()->create();

        $this->actingAs($tenantUser)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_superadmin_can_access_dashboard_and_sees_metrics(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $plan = Plan::factory()->create(['price' => 199.90]);

        $activeClinic = Clinic::factory()->for($plan)->create(['subscription_status' => 'active']);
        $trialClinic = Clinic::factory()->for($plan)->create(['subscription_status' => 'trial']);
        $pastDueClinic = Clinic::factory()->for($plan)->create(['subscription_status' => 'past_due']);

        Appointment::factory()->for($activeClinic)->create(['scheduled_at' => now()]);

        $response = $this->actingAs($superAdmin)->get('/admin');

        $response->assertOk()
            ->assertSee('Painel Global do SuperAdmin')
            ->assertSee('R$ 199,90')
            ->assertSee('3') // total clinics
            ->assertSee($activeClinic->name)
            ->assertSee($trialClinic->name);
    }
}
