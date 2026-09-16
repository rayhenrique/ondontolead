<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use App\Models\UserReleaseRead;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserReleaseReadPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_user_can_manage_only_own_release_read_receipts(): void
    {
        $user = User::factory()->tenant()->create();
        $otherUser = User::factory()->tenant()->create();
        $ownRead = UserReleaseRead::factory()->for($user)->create();
        $otherRead = UserReleaseRead::factory()->for($otherUser)->create();
        $gate = Gate::forUser($user);

        $this->assertFalse($gate->allows('viewAny', UserReleaseRead::class));
        $this->assertTrue($gate->allows('create', UserReleaseRead::class));
        $this->assertTrue($gate->allows('view', $ownRead));
        $this->assertTrue($gate->allows('update', $ownRead));
        $this->assertTrue($gate->allows('delete', $ownRead));
        $this->assertFalse($gate->allows('view', $otherRead));
        $this->assertFalse($gate->allows('update', $otherRead));
        $this->assertFalse($gate->allows('delete', $otherRead));
    }

    public function test_superadmin_can_manage_any_release_read_receipt(): void
    {
        $read = UserReleaseRead::factory()->create();
        $gate = Gate::forUser(User::factory()->superAdmin()->create());

        $this->assertTrue($gate->allows('viewAny', UserReleaseRead::class));
        $this->assertTrue($gate->allows('view', $read));
        $this->assertTrue($gate->allows('delete', $read));
    }
}
