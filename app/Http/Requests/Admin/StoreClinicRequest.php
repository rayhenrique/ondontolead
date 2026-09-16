<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClinicRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('clinics', 'slug')],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'past_due', 'canceled'])],
            'trial_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'user_name' => ['required', 'string', 'max:150'],
            'user_email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'user_password' => ['required', 'string', 'min:8'],
        ];
    }
}
