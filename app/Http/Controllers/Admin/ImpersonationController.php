<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImpersonationController extends Controller
{
    public function impersonate(Request $request, Clinic $clinic): RedirectResponse
    {
        $admin = $request->user();
        abort_unless($admin?->is_superadmin === true, 403);

        $clinicUser = $clinic->users()->first();

        if ($clinicUser === null) {
            $clinicUser = User::query()->create([
                'clinic_id' => $clinic->id,
                'name' => 'Gestor '.$clinic->name,
                'email' => 'gestor+'.Str::slug($clinic->slug).'@odontolead.local',
                'password' => Hash::make(Str::random(32)),
                'is_superadmin' => false,
            ]);
        }

        session(['impersonator_id' => $admin->id]);
        Auth::guard('web')->login($clinicUser);

        return redirect()
            ->route('app.dashboard')
            ->with('status', "Você agora está personificando a clínica {$clinic->name}.");
    }

    public function leave(Request $request): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');
        abort_unless($impersonatorId !== null, 403, 'Nenhuma personificação ativa.');

        $admin = User::query()
            ->where('is_superadmin', true)
            ->findOrFail($impersonatorId);

        session()->forget('impersonator_id');
        Auth::guard('web')->login($admin);

        return redirect()
            ->route('admin.clinics.index')
            ->with('status', 'Personificação encerrada. Você voltou à sua conta de SuperAdmin.');
    }
}
