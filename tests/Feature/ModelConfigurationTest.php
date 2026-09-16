<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ModelConfigurationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_clinic_encrypts_and_hides_the_ai_api_key(): void
    {
        $plan = Plan::query()->create([
            'name' => 'Test Plan',
            'slug' => 'test-plan',
            'price' => 99.9,
            'max_appointments_per_month' => 100,
            'is_active' => true,
        ]);

        $clinic = Clinic::query()->create([
            'plan_id' => $plan->id,
            'name' => 'Test Clinic',
            'slug' => 'test-clinic',
            'whatsapp_number' => '5585999999999',
            'ai_provider' => 'openai',
            'ai_api_key' => 'secret-api-key',
            'subscription_status' => 'trial',
            'trial_ends_at' => '2026-10-15 12:00:00',
        ]);

        $storedApiKey = DB::table('clinics')->where('id', $clinic->id)->value('ai_api_key');
        $freshClinic = Clinic::query()->findOrFail($clinic->id);

        $this->assertIsString($storedApiKey);
        $this->assertNotSame('secret-api-key', $storedApiKey);
        $this->assertSame('secret-api-key', $freshClinic->ai_api_key);
        $this->assertArrayNotHasKey('ai_api_key', $freshClinic->toArray());
        $this->assertInstanceOf(CarbonInterface::class, $freshClinic->trial_ends_at);
        $this->assertSame('99.90', $plan->price);
    }

    public function test_system_models_persist_their_nonstandard_columns(): void
    {
        $setting = SystemSetting::query()->create([
            'key' => 'system_name',
            'value' => 'OdontoLead AI',
        ]);
        $paymentLog = PaymentLog::query()->create([
            'event_id' => 'evt_123',
            'payload' => ['status' => 'approved'],
            'status' => 'processed',
        ]);

        $this->assertSame('system_name', $setting->getKey());
        $this->assertSame(['status' => 'approved'], $paymentLog->payload);
        $this->assertNull($paymentLog->updated_at);
        $this->assertModelExists($setting);
        $this->assertModelExists($paymentLog);
    }

    public function test_mass_assignment_cannot_grant_superadmin_privileges(): void
    {
        $user = new User;

        $user->fill([
            'name' => 'Tenant User',
            'email' => 'tenant@example.com',
            'password' => 'password',
            'is_superadmin' => true,
        ]);

        $this->assertNull($user->is_superadmin);
    }
}
