<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('plans', 'slug')],
            'price' => ['required', 'numeric', 'min:0'],
            'max_appointments_per_month' => ['required', 'integer', 'min:1'],
            'mp_plan_id' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ];
    }
}
