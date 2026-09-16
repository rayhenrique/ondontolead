<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentManagementController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $clinic = $user->clinic ?? ($user->is_superadmin ? Clinic::query()->first() : null);
        abort_unless($clinic !== null, 403, 'Nenhuma clínica associada.');

        $status = $request->string('status')->trim()->value();
        $search = $request->string('search')->trim()->value();
        $date = $request->string('date')->trim()->value();

        $appointments = Appointment::query()
            ->where('clinic_id', $clinic->id)
            ->with('triageRecord')
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($sub) use ($search): void {
                    $sub->where('patient_name', 'like', "%{$search}%")
                        ->orWhere('patient_phone', 'like', "%{$search}%");
                });
            })
            ->when($date !== '', function ($query) use ($date): void {
                $query->whereDate('scheduled_at', $date);
            })
            ->orderByDesc('scheduled_at')
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Appointment::query()
            ->where('clinic_id', $clinic->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $totalCount = array_sum($statusCounts);

        return view('clinic.appointments.index', compact(
            'clinic',
            'appointments',
            'status',
            'search',
            'date',
            'statusCounts',
            'totalCount',
        ));
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $clinic = $user->clinic ?? ($user->is_superadmin ? $appointment->clinic : null);

        abort_unless($clinic !== null && $appointment->clinic_id === $clinic->id, 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'canceled', 'no_show'])],
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        $statusLabels = [
            'pending' => 'Pendente',
            'confirmed' => 'Confirmado',
            'completed' => 'Concluído',
            'canceled' => 'Cancelado',
            'no_show' => 'Não compareceu',
        ];

        $label = $statusLabels[$appointment->status] ?? $appointment->status;

        return redirect()
            ->back()
            ->with('status', "Status do agendamento de {$appointment->patient_name} alterado para '{$label}'.");
    }
}
