<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TrialRegistrationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_new_user_registration_provisions_clinic_with_14_day_trial_without_credit_card(): void
    {
        $plan = Plan::factory()->create([
            'name' => 'Essencial',
            'slug' => 'essencial',
            'is_active' => true,
        ]);

        $response = $this->post('/register', [
            'name' => 'Dra. Roberta Dias',
            'email' => 'roberta@odontodias.com.br',
            'clinic_name' => 'Odonto Dias Centro',
            'whatsapp_number' => '11988887777',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
        ]);

        $this->assertAuthenticated();

        $user = User::query()->where('email', 'roberta@odontodias.com.br')->firstOrFail();
        $this->assertNotNull($user->clinic_id);

        $clinic = $user->clinic;
        $this->assertSame('Odonto Dias Centro', $clinic->name);
        $this->assertSame('odonto-dias-centro', $clinic->slug);
        $this->assertSame('trial', $clinic->subscription_status);
        $this->assertNull($clinic->mp_subscription_id); // Sem cartão de crédito no trial

        // Verifica que o trial expira em aproximadamente 14 dias
        $this->assertNotNull($clinic->trial_ends_at);
        $this->assertSame(14, (int) round(now()->diffInDays($clinic->trial_ends_at, false)));

        // Acesso imediato ao painel da clínica sem bloqueio 403
        $appResponse = $this->actingAs($user)->get('/app');
        $appResponse->assertOk();
        $appResponse->assertSee('Período de Testes');
        $appResponse->assertSee('Sem Cartão de Crédito');
        $appResponse->assertSee('14 dias grátis');
    }

    public function test_superadmin_clinic_creation_defaults_to_14_days_trial_when_trial_days_omitted(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($superAdmin)->post('/admin/clinics', [
            'name' => 'Clínica Sorriso Feliz',
            'slug' => 'clinica-sorriso-feliz',
            'whatsapp_number' => '+5511977776666',
            'plan_id' => $plan->id,
            'subscription_status' => 'trial',
            // trial_days omitido propositalmente para testar o default de 14 dias
            'user_name' => 'Dr. Marcelo Silva',
            'user_email' => 'marcelo@sorrisofeliz.com.br',
            'user_password' => 'secret12345',
        ]);

        $response->assertRedirect('/admin/clinics');

        $clinic = Clinic::query()->where('slug', 'clinica-sorriso-feliz')->firstOrFail();
        $this->assertSame('trial', $clinic->subscription_status);
        $this->assertNotNull($clinic->trial_ends_at);
        $this->assertSame(14, (int) round(now()->diffInDays($clinic->trial_ends_at, false)));
    }

    public function test_landing_page_presents_14_day_free_trial_and_no_credit_card(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('14 dias grátis');
        $response->assertSee('Sem cartão de crédito');
    }
}
