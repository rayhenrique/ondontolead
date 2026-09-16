<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TenantSubscriptionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_unauthenticated_request_redirects_to_login(): void
    {
        $this->get('/app')
            ->assertRedirect('/login');
    }

    public function test_active_subscription_can_access_tenant_area(): void
    {
        $user = User::factory()->tenant(Clinic::factory()->active()->create())->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertOk();
    }

    public function test_unexpired_trial_can_access_tenant_area(): void
    {
        $clinic = Clinic::factory()->create([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addMinute(),
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertOk();
    }

    public function test_trial_without_expiration_can_access_tenant_area_during_onboarding(): void
    {
        $clinic = Clinic::factory()->create([
            'subscription_status' => 'trial',
            'trial_ends_at' => null,
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertOk();
    }

    public function test_expired_trial_is_forbidden_from_tenant_area(): void
    {
        $clinic = Clinic::factory()->create([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->subSecond(),
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertForbidden();
    }

    public function test_blocked_tenant_cannot_bypass_subscription_through_legacy_dashboard(): void
    {
        $clinic = Clinic::factory()->pastDue()->create();
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertForbidden();
    }

    #[DataProvider('blockedSubscriptionStatuses')]
    public function test_blocked_subscription_status_is_forbidden_from_tenant_area(string $status): void
    {
        $clinic = Clinic::factory()->create(['subscription_status' => $status]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertForbidden();
    }

    public function test_user_without_clinic_is_forbidden_from_tenant_area(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/app')
            ->assertForbidden();
    }

    public function test_superadmin_bypasses_tenant_subscription_check(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get('/app')
            ->assertOk();
    }

    public static function blockedSubscriptionStatuses(): array
    {
        return [
            'past due' => ['past_due'],
            'canceled' => ['canceled'],
        ];
    }
}
