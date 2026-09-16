<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IsSuperAdminTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_unauthenticated_request_redirects_to_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_is_forbidden_from_admin_area(): void
    {
        $user = User::factory()->tenant()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_superadmin_can_access_admin_area(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get('/admin')
            ->assertOk();
    }
}
