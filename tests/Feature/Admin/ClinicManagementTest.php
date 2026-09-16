<?php

namespace Tests\Feature\Admin;

use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ClinicManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_superadmin_can_view_clinics_list_and_filter_by_status(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create();

        $activeClinic = Clinic::factory()->for($plan)->create([
            'name' => 'Clínica Alfa',
            'subscription_status' => 'active',
        ]);

        $trialClinic = Clinic::factory()->for($plan)->create([
            'name' => 'Clínica Beta',
            'subscription_status' => 'trial',
        ]);

        $this->actingAs($superAdmin)
            ->get('/admin/clinics')
            ->assertOk()
            ->assertSee('Clínica Alfa')
            ->assertSee('Clínica Beta');

        // Filter by active
        $this->actingAs($superAdmin)
            ->get('/admin/clinics?status=active')
            ->assertOk()
            ->assertSee('Clínica Alfa')
            ->assertDontSee('Clínica Beta');

        // Search by name
        $this->actingAs($superAdmin)
            ->get('/admin/clinics?search=Beta')
            ->assertOk()
            ->assertDontSee('Clínica Alfa')
            ->assertSee('Clínica Beta');
    }

    public function test_superadmin_can_create_new_clinic_with_initial_user(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($superAdmin)->post('/admin/clinics', [
            'name' => 'Odonto Sorriso Novo',
            'slug' => 'odonto-sorriso-novo',
            'whatsapp_number' => '+5511988887777',
            'plan_id' => $plan->id,
            'subscription_status' => 'trial',
            'trial_days' => 15,
            'user_name' => 'Dr. Fernando Dias',
            'user_email' => 'fernando@sorrisonovo.com',
            'user_password' => 'secret12345',
        ]);

        $response->assertRedirect('/admin/clinics')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('clinics', [
            'name' => 'Odonto Sorriso Novo',
            'slug' => 'odonto-sorriso-novo',
            'whatsapp_number' => '+5511988887777',
            'subscription_status' => 'trial',
        ]);

        $clinic = Clinic::query()->where('slug', 'odonto-sorriso-novo')->firstOrFail();
        $this->assertNotNull($clinic->trial_ends_at);

        $this->assertDatabaseHas('users', [
            'clinic_id' => $clinic->id,
            'email' => 'fernando@sorrisonovo.com',
            'name' => 'Dr. Fernando Dias',
            'is_superadmin' => false,
        ]);
    }

    public function test_superadmin_can_update_clinic_and_extend_trial(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan1 = Plan::factory()->create();
        $plan2 = Plan::factory()->create();

        $clinic = Clinic::factory()->for($plan1)->create([
            'name' => 'Clínica Antiga',
            'slug' => 'clinica-antiga',
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(2),
        ]);

        $response = $this->actingAs($superAdmin)->put("/admin/clinics/{$clinic->id}", [
            'name' => 'Clínica Atualizada',
            'slug' => 'clinica-atualizada',
            'whatsapp_number' => '+5511911112222',
            'plan_id' => $plan2->id,
            'subscription_status' => 'active',
            'extend_trial_days' => 10,
        ]);

        $response->assertRedirect('/admin/clinics')
            ->assertSessionHas('status');

        $clinic->refresh();
        $this->assertSame('Clínica Atualizada', $clinic->name);
        $this->assertSame('clinica-atualizada', $clinic->slug);
        $this->assertSame($plan2->id, $clinic->plan_id);
        $this->assertSame('active', $clinic->subscription_status);
        $this->assertTrue($clinic->trial_ends_at->isFuture());
    }

    public function test_superadmin_can_toggle_clinic_status(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $clinic = Clinic::factory()->active()->create();

        $this->actingAs($superAdmin)
            ->patch("/admin/clinics/{$clinic->id}/status", [
                'subscription_status' => 'past_due',
            ])
            ->assertRedirect();

        $clinic->refresh();
        $this->assertSame('past_due', $clinic->subscription_status);
    }
}
