<?php

namespace Tests\Feature\Clinic;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ClinicSettingTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_cannot_access_clinic_settings(): void
    {
        $this->get('/app/configuracoes')
            ->assertRedirect('/login');
    }

    public function test_tenant_user_can_view_settings_form(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'name' => 'Clínica Odonto Teste',
            'slug' => 'odonto-teste',
            'whatsapp_number' => '11977776666',
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->get('/app/configuracoes')
            ->assertOk()
            ->assertSee('Clínica Odonto Teste')
            ->assertSee('odonto-teste')
            ->assertSee('11977776666');
    }

    public function test_tenant_user_can_update_name_and_whatsapp(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'name' => 'Nome Antigo',
            'slug' => 'slug-antigo',
            'whatsapp_number' => '11900000000',
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->put('/app/configuracoes', [
                'name' => 'Nome Renovado',
                'slug' => 'slug-antigo',
                'whatsapp_number' => '11911112222',
                'ai_provider' => 'none',
            ])
            ->assertRedirect(route('app.settings.edit'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('clinics', [
            'id' => $clinic->id,
            'name' => 'Nome Renovado',
            'whatsapp_number' => '11911112222',
        ]);
    }

    public function test_slug_uniqueness_is_enforced_excluding_current_clinic(): void
    {
        $otherClinic = Clinic::factory()->active()->create([
            'slug' => 'slug-em-uso',
        ]);

        $clinic = Clinic::factory()->active()->create([
            'slug' => 'meu-slug',
        ]);
        $user = User::factory()->tenant($clinic)->create();

        // Trying to use another clinic's slug
        $this->actingAs($user)
            ->put('/app/configuracoes', [
                'name' => $clinic->name,
                'slug' => 'slug-em-uso',
                'whatsapp_number' => $clinic->whatsapp_number,
                'ai_provider' => 'none',
            ])
            ->assertSessionHasErrors(['slug']);

        // Keeping own slug should pass
        $this->actingAs($user)
            ->put('/app/configuracoes', [
                'name' => 'Nome Atualizado',
                'slug' => 'meu-slug',
                'whatsapp_number' => $clinic->whatsapp_number,
                'ai_provider' => 'none',
            ])
            ->assertSessionHasNoErrors();

        // Updating to unique new slug
        $this->actingAs($user)
            ->put('/app/configuracoes', [
                'name' => 'Nome Atualizado',
                'slug' => 'novo-slug-unico',
                'whatsapp_number' => $clinic->whatsapp_number,
                'ai_provider' => 'none',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('clinics', [
            'id' => $clinic->id,
            'slug' => 'novo-slug-unico',
        ]);
    }

    public function test_tenant_user_can_configure_byok_ai_key(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'ai_provider' => 'none',
            'ai_api_key' => null,
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->put('/app/configuracoes', [
                'name' => $clinic->name,
                'slug' => $clinic->slug,
                'whatsapp_number' => $clinic->whatsapp_number,
                'ai_provider' => 'gemini',
                'ai_api_key' => 'AIzaSyGeminiApiKeyTestVal123',
            ])
            ->assertRedirect(route('app.settings.edit'))
            ->assertSessionHas('status');

        $refreshed = $clinic->fresh();
        $this->assertSame('gemini', $refreshed->ai_provider);
        $this->assertSame('AIzaSyGeminiApiKeyTestVal123', $refreshed->ai_api_key);
    }

    public function test_setting_ai_provider_to_none_clears_api_key(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'ai_provider' => 'openai',
            'ai_api_key' => 'sk-test-secret-key',
        ]);
        $user = User::factory()->tenant($clinic)->create();

        $this->actingAs($user)
            ->put('/app/configuracoes', [
                'name' => $clinic->name,
                'slug' => $clinic->slug,
                'whatsapp_number' => $clinic->whatsapp_number,
                'ai_provider' => 'none',
            ])
            ->assertRedirect(route('app.settings.edit'));

        $refreshed = $clinic->fresh();
        $this->assertSame('none', $refreshed->ai_provider);
        $this->assertNull($refreshed->ai_api_key);
    }
}
