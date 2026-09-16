<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->clinic_id !== null || $this->user()?->is_superadmin === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $clinic = $this->user()?->clinic;
        $clinicId = $clinic?->id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('clinics', 'slug')->ignore($clinicId),
            ],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'ai_provider' => ['required', Rule::in(['none', 'gemini', 'openai'])],
            'ai_api_key' => ['nullable', 'string', 'max:255'],
        ];
    }
}
