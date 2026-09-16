<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ReleaseSyncTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_sync_command_synchronizes_releases_from_markdown(): void
    {
        $this->artisan('releases:sync')
            ->expectsOutputToContain('Total de 8 releases sincronizadas com sucesso')
            ->assertSuccessful();

        $this->assertDatabaseHas('app_releases', [
            'version' => 'v1.0.0',
            'title' => 'Release Oficial de Produção do MVP — Isolamento, Concorrência e Webhooks',
            'show_modal' => true,
        ]);

        $this->assertDatabaseHas('app_releases', [
            'version' => 'v0.7.0',
            'title' => 'Landing Page Pública, Triagem Interativa e Agendamento Online',
            'show_modal' => false,
        ]);

        $this->assertDatabaseHas('app_releases', [
            'version' => 'v0.1.0',
            'title' => 'Base de Desenvolvimento e Setup Inicial',
            'show_modal' => false,
        ]);

        $this->assertDatabaseCount('app_releases', 8);
    }

    public function test_novidades_page_displays_synced_releases_to_tenant(): void
    {
        $this->artisan('releases:sync')->assertSuccessful();

        $clinic = Clinic::factory()->active()->create();
        $user = User::factory()->tenant($clinic)->create();

        $response = $this->actingAs($user)->get('/app/novidades');

        $response->assertOk();
        $response->assertSee('v1.0.0');
        $response->assertSee('Release Oficial de Produção do MVP');
        $response->assertSee('v0.7.0');
    }
}
