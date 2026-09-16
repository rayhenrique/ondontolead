<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\AppRelease;
use App\Models\Clinic;
use App\Models\User;
use App\Models\UserReleaseRead;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CheckUnreadReleasesTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_latest_published_unread_release_is_shared_and_stored_in_session(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $user = $this->activeTenantUser();
        AppRelease::factory()->create([
            'version' => 'v0.1.0',
            'released_at' => now()->subDay(),
        ]);
        $latestRelease = AppRelease::factory()->create([
            'version' => 'v0.2.0',
            'released_at' => now()->subHour(),
        ]);

        $this->actingAs($user)
            ->get('/app')
            ->assertOk()
            ->assertSessionHas('unread_app_release_id', $latestRelease->getKey())
            ->assertViewHas(
                'unreadAppRelease',
                fn (AppRelease $release): bool => $release->is($latestRelease),
            );
    }

    public function test_release_read_by_current_user_is_not_selected(): void
    {
        $user = $this->activeTenantUser();
        $release = AppRelease::factory()->create();
        UserReleaseRead::factory()->for($user)->for($release, 'appRelease')->create();

        $this->actingAs($user)
            ->withSession(['unread_app_release_id' => $release->getKey()])
            ->get('/app')
            ->assertOk()
            ->assertSessionMissing('unread_app_release_id')
            ->assertViewHas('unreadAppRelease', null);
    }

    public function test_another_users_read_does_not_hide_release(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $currentUser = User::factory()->tenant($clinic)->create();
        $otherUser = User::factory()->tenant($clinic)->create();
        $release = AppRelease::factory()->create();
        UserReleaseRead::factory()->for($otherUser)->for($release, 'appRelease')->create();

        $this->actingAs($currentUser)
            ->get('/app')
            ->assertSessionHas('unread_app_release_id', $release->getKey());
    }

    public function test_hidden_and_future_releases_are_not_selected(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $user = $this->activeTenantUser();
        AppRelease::factory()->withoutModal()->create([
            'version' => 'v0.3.0',
            'released_at' => now()->subMinute(),
        ]);
        AppRelease::factory()->create([
            'version' => 'v0.4.0',
            'released_at' => now()->addMinute(),
        ]);

        $this->actingAs($user)
            ->withSession(['unread_app_release_id' => 999])
            ->get('/app')
            ->assertOk()
            ->assertSessionMissing('unread_app_release_id')
            ->assertViewHas('unreadAppRelease', null);
    }

    private function activeTenantUser(): User
    {
        $clinic = Clinic::factory()->active()->create();

        return User::factory()->tenant($clinic)->create();
    }
}
