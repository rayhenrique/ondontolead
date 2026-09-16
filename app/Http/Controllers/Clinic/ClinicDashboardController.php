<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicSchedule;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClinicDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $clinic = $user->clinic;

        // Fallback for SuperAdmin visiting /app without impersonating
        if ($clinic === null && $user->is_superadmin) {
            $clinic = Clinic::query()->first() ?? new Clinic([
                'id' => 0,
                'name' => 'Clínica Demo (SuperAdmin)',
                'slug' => 'demo',
                'whatsapp_number' => '(00) 00000-0000',
                'status' => 'active',
                'ai_provider' => 'none',
            ]);
        }

        abort_unless($clinic !== null, 403, 'Nenhuma clínica associada a este usuário.');

        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        $sevenDaysAhead = $now->copy()->addDays(7)->endOfDay();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        // Scoped to this clinic automatically or explicitly
        $clinicId = $clinic->id;

        $todayCount = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->whereBetween('scheduled_at', [$todayStart, $todayEnd])
            ->count();

        $upcomingWeekCount = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->whereBetween('scheduled_at', [$now, $sevenDaysAhead])
            ->count();

        $monthCount = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->whereBetween('scheduled_at', [$monthStart, $monthEnd])
            ->count();

        $statusCounts = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $nextAppointments = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->where('scheduled_at', '>=', $now)
            ->with('triageRecord')
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $hasSchedule = ClinicSchedule::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->where('is_active', true)
            ->exists();

        return view('clinic.dashboard', compact(
            'clinic',
            'todayCount',
            'upcomingWeekCount',
            'monthCount',
            'statusCounts',
            'nextAppointments',
            'hasSchedule',
        ));
    }
}
