<?php

namespace App\Actions\Fortify;

use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida e cria um novo usuário gestor provisionando sua clínica em trial de 14 dias (sem cartão).
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'clinic_name' => ['nullable', 'string', 'max:150'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input): User {
            // Obtém o plano padrão ou cria plano Essencial de fallback se necessário
            $plan = Plan::query()->where('is_active', true)->orderBy('price')->first()
                ?? Plan::query()->firstOrCreate(
                    ['slug' => 'essencial'],
                    [
                        'name' => 'Essencial',
                        'price' => 99.90,
                        'max_appointments_per_month' => 100,
                        'is_active' => true,
                    ]
                );

            $clinicName = ! empty($input['clinic_name'])
                ? trim((string) $input['clinic_name'])
                : 'Clínica '.trim($input['name']);

            $baseSlug = Str::slug($clinicName);
            if ($baseSlug === '') {
                $baseSlug = 'clinica-'.Str::lower(Str::random(6));
            }

            $slug = $baseSlug;
            $counter = 1;
            while (Clinic::withoutGlobalScopes()->where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $whatsapp = ! empty($input['whatsapp_number'])
                ? preg_replace('/\D/', '', (string) $input['whatsapp_number'])
                : '5511999999999';

            // Provisiona a clínica em período de testes gratuito de 14 dias sem cartão de crédito
            $clinic = Clinic::withoutGlobalScopes()->create([
                'plan_id' => $plan->id,
                'name' => $clinicName,
                'slug' => $slug,
                'whatsapp_number' => $whatsapp,
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'ai_provider' => 'none',
                'mp_subscription_id' => null, // Sem necessidade de cartão de crédito no trial
            ]);

            return User::create([
                'clinic_id' => $clinic->id,
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'is_superadmin' => false,
            ]);
        });
    }
}
