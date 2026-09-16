<?php

namespace Tests\Feature\Admin;

use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PlanManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_superadmin_can_view_plans_index(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create(['name' => 'Plano Premium Especial']);

        $this->actingAs($superAdmin)
            ->get('/admin/plans')
            ->assertOk()
            ->assertSee('Plano Premium Especial');
    }

    public function test_superadmin_can_create_plan(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->post('/admin/plans', [
            'name' => 'Plano Enterprise',
            'slug' => 'enterprise',
            'price' => 499.00,
            'max_appointments_per_month' => 500,
            'mp_plan_id' => 'mp_plan_12345',
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/plans')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('plans', [
            'name' => 'Plano Enterprise',
            'slug' => 'enterprise',
            'price' => 499.00,
            'max_appointments_per_month' => 500,
            'mp_plan_id' => 'mp_plan_12345',
            'is_active' => 1,
        ]);
    }

    public function test_superadmin_can_update_plan(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create([
            'name' => 'Plano Base',
            'slug' => 'plano-base',
            'price' => 99.00,
        ]);

        $response = $this->actingAs($superAdmin)->put("/admin/plans/{$plan->id}", [
            'name' => 'Plano Base Atualizado',
            'slug' => 'plano-base',
            'price' => 119.00,
            'max_appointments_per_month' => 80,
            'is_active' => false,
        ]);

        $response->assertRedirect('/admin/plans');

        $plan->refresh();
        $this->assertSame('Plano Base Atualizado', $plan->name);
        $this->assertEquals(119.00, $plan->price);
        $this->assertFalse($plan->is_active);
    }

    public function test_cannot_delete_plan_with_associated_clinics(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create();
        Clinic::factory()->for($plan)->create();

        $response = $this->actingAs($superAdmin)->delete("/admin/plans/{$plan->id}");

        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('plans', ['id' => $plan->id]);
    }

    public function test_can_delete_empty_plan(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($superAdmin)->delete("/admin/plans/{$plan->id}");

        $response->assertRedirect('/admin/plans');
        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
    }
}
