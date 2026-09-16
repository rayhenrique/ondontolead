<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSystemSettingsRequest;
use App\Services\SystemSettingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SystemSettingController extends Controller
{
    public function edit(SystemSettingService $settingService): View
    {
        $settings = $settingService->all();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(UpdateSystemSettingsRequest $request, SystemSettingService $settingService): RedirectResponse
    {
        $settingService->setMany($request->validated());

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Configurações do sistema atualizadas com sucesso.');
    }
}
