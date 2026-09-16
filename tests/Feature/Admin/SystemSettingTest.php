<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\SystemSettingService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SystemSettingTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_superadmin_can_view_settings_form(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee('Configurações Globais do Sistema');
    }

    public function test_superadmin_can_update_settings_and_cache_is_refreshed(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $service = app(SystemSettingService::class);

        // Pre-warm cache
        $service->all();
        $this->assertTrue(Cache::has(SystemSettingService::CACHE_KEY));

        $response = $this->actingAs($superAdmin)->put('/admin/settings', [
            'system_name' => 'OdontoLead Enterprise Pro',
            'system_logo_url' => 'https://example.com/brand-logo.png',
            'system_favicon_url' => 'https://example.com/favicon.ico',
            'system_footer_text' => '© 2026 Minha Empresa - Todos os direitos reservados.',
        ]);

        $response->assertRedirect('/admin/settings')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'system_name',
            'value' => 'OdontoLead Enterprise Pro',
        ]);

        // After saving, cache was cleared and will re-fetch updated data
        $this->assertSame('OdontoLead Enterprise Pro', $service->get('system_name'));
        $this->assertSame('https://example.com/brand-logo.png', $service->get('system_logo_url'));
    }
}
