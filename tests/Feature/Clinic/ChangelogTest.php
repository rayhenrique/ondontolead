<?php

namespace Tests\Feature\Clinic;

use App\Livewire\AppReleaseModal;
use App\Models\AppRelease;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class ChangelogTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_access_novidades(): void
    {
        $this->get('/app/novidades')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_can_view_published_releases(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $published = AppRelease::factory()->create([
            'version' => 'v1.2.0',
            'title' => 'Novo Sistema de Triagem IA',
            'released_at' => Carbon::yesterday(),
        ]);

        $future = AppRelease::factory()->create([
            'version' => 'v2.0.0-draft',
            'title' => 'Versão Futura Secreta',
            'released_at' => Carbon::tomorrow(),
        ]);

        $this->actingAs($user)
            ->get('/app/novidades')
            ->assertOk()
            ->assertSee('v1.2.0')
            ->assertSee('Novo Sistema de Triagem IA')
            ->assertDontSee('Versão Futura Secreta');
    }

    public function test_tenant_user_can_mark_release_as_read(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $release = AppRelease::factory()->create([
            'version' => 'v1.1.0',
            'released_at' => Carbon::yesterday(),
        ]);

        $this->actingAs($user)
            ->post("/app/novidades/{$release->id}/read")
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('user_release_reads', [
            'user_id' => $user->id,
            'app_release_id' => $release->id,
        ]);
    }

    public function test_app_release_modal_shows_unread_release(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $release = AppRelease::factory()->create([
            'version' => 'v0.6.0',
            'title' => 'Módulo da Clínica e Triagens',
            'content' => 'Lançamento completo dos painéis de clínica.',
            'released_at' => Carbon::yesterday(),
        ]);

        $this->actingAs($user);

        Livewire::test(AppReleaseModal::class)
            ->assertSet('isOpen', true)
            ->assertSet('version', 'v0.6.0')
            ->assertSet('title', 'Módulo da Clínica e Triagens');
    }

    public function test_rule_rn03_modal_dismiss_marks_release_as_read_and_does_not_show_again(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $release = AppRelease::factory()->create([
            'version' => 'v0.6.0',
            'released_at' => Carbon::yesterday(),
        ]);

        $this->actingAs($user);

        // First open and dismiss
        Livewire::test(AppReleaseModal::class)
            ->assertSet('isOpen', true)
            ->call('dismiss')
            ->assertSet('isOpen', false);

        $this->assertDatabaseHas('user_release_reads', [
            'user_id' => $user->id,
            'app_release_id' => $release->id,
        ]);

        // Re-mount: RN03 ensures modal is not shown again for this user
        Livewire::test(AppReleaseModal::class)
            ->assertSet('isOpen', false)
            ->assertSet('releaseId', null);
    }
}
