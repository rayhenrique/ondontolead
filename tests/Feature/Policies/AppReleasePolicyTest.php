<?php

namespace Tests\Feature\Policies;

use App\Models\AppRelease;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AppReleasePolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_tenant_can_read_but_cannot_manage_releases(): void
    {
        $release = AppRelease::factory()->create();
        $gate = Gate::forUser(User::factory()->tenant()->create());

        $this->assertTrue($gate->allows('viewAny', AppRelease::class));
        $this->assertTrue($gate->allows('view', $release));
        $this->assertFalse($gate->allows('create', AppRelease::class));
        $this->assertFalse($gate->allows('update', $release));
        $this->assertFalse($gate->allows('delete', $release));
    }

    public function test_superadmin_can_manage_releases(): void
    {
        $release = AppRelease::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('viewAny', AppRelease::class));
        $this->assertTrue($gate->allows('view', $release));
        $this->assertTrue($gate->allows('create', AppRelease::class));
        $this->assertTrue($gate->allows('update', $release));
        $this->assertTrue($gate->allows('delete', $release));
    }
}
