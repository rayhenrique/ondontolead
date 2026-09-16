<?php

namespace App\Http\Requests\Admin;

use App\Models\Clinic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_superadmin === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Clinic $clinic */
        $clinic = $this->route('clinic');

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('clinics', 'slug')->ignore($clinic->id),
            ],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'past_due', 'canceled'])],
            'trial_ends_at' => ['nullable', 'date'],
            'extend_trial_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ];
    }
}
