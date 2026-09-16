<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppReleaseRequest extends FormRequest
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
            'version' => [
                'required',
                'string',
                'max:20',
                'regex:/^v?\d+\.\d+\.\d+(-[a-zA-Z0-9.]+)?$/',
                Rule::unique('app_releases', 'version'),
            ],
            'title' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
            'show_modal' => ['boolean'],
            'released_at' => ['required', 'date'],
        ];
    }
}
