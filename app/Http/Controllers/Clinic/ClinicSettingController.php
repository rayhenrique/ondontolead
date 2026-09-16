<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\UpdateClinicSettingsRequest;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClinicSettingController extends Controller
{
    public function edit(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $clinic = $user->clinic ?? ($user->is_superadmin ? Clinic::query()->first() : null);
        abort_unless($clinic !== null, 403, 'Nenhuma clínica associada.');

        return view('clinic.settings.edit', compact('clinic'));
    }

    public function update(UpdateClinicSettingsRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $clinic = $user->clinic ?? ($user->is_superadmin ? Clinic::query()->first() : null);
        abort_unless($clinic !== null, 403, 'Nenhuma clínica associada.');

        $validated = $request->validated();

        $attributes = [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'ai_provider' => $validated['ai_provider'],
        ];

        if ($validated['ai_provider'] === 'none') {
            $attributes['ai_api_key'] = null;
        } elseif (! empty($validated['ai_api_key'])) {
            $attributes['ai_api_key'] = $validated['ai_api_key'];
        }

        $clinic->update($attributes);

        return redirect()
            ->route('app.settings.edit')
            ->with('status', 'Configurações da clínica atualizadas com sucesso.');
    }
}
