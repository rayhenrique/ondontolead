<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingsRequest extends FormRequest
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
            'system_name' => ['required', 'string', 'max:100'],
            'system_logo_url' => ['nullable', 'string', 'max:255'],
            'system_favicon_url' => ['nullable', 'string', 'max:255'],
            'system_footer_text' => ['nullable', 'string', 'max:255'],
        ];
    }
}
