<?php

namespace Tests\Feature\Admin;

use App\Models\AppRelease;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AppReleaseManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_superadmin_can_view_releases_index(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        AppRelease::factory()->create([
            'version' => 'v2.0.0',
            'title' => 'Lançamento da Versão 2',
        ]);

        $this->actingAs($superAdmin)
            ->get('/admin/releases')
            ->assertOk()
            ->assertSee('v2.0.0')
            ->assertSee('Lançamento da Versão 2');
    }

    public function test_superadmin_can_create_release(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->post('/admin/releases', [
            'version' => 'v1.5.0',
            'title' => 'Nova funcionalidade de Triagem',
            'content' => '## Melhorias\n- Triagem com IA Gemini e OpenAI',
            'show_modal' => true,
            'released_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/admin/releases')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('app_releases', [
            'version' => 'v1.5.0',
            'title' => 'Nova funcionalidade de Triagem',
            'show_modal' => 1,
        ]);
    }

    public function test_superadmin_can_update_release(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $release = AppRelease::factory()->create([
            'version' => 'v1.1.0',
            'title' => 'Título Inicial',
        ]);

        $response = $this->actingAs($superAdmin)->put("/admin/releases/{$release->id}", [
            'version' => 'v1.1.0',
            'title' => 'Título Corrigido',
            'content' => 'Novo conteúdo descritivo',
            'show_modal' => false,
            'released_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect('/admin/releases');

        $release->refresh();
        $this->assertSame('Título Corrigido', $release->title);
        $this->assertFalse($release->show_modal);
    }

    public function test_superadmin_can_delete_release(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $release = AppRelease::factory()->create();

        $response = $this->actingAs($superAdmin)->delete("/admin/releases/{$release->id}");

        $response->assertRedirect('/admin/releases');
        $this->assertDatabaseMissing('app_releases', ['id' => $release->id]);
    }
}
