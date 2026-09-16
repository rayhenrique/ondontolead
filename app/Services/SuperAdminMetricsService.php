<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class SuperAdminMetricsService
{
    /**
     * @return array{
     *     total_clinics: int,
     *     active_clinics: int,
     *     trial_clinics: int,
     *     delinquent_clinics: int,
     *     canceled_clinics: int,
     *     mrr: float,
     *     total_appointments: int,
     *     month_appointments: int,
     *     recent_clinics: Collection<int, Clinic>
     * }
     */
    public function getMetrics(): array
    {
        $clinicsCountByStatus = Clinic::query()
            ->selectRaw('subscription_status, COUNT(*) as count')
            ->groupBy('subscription_status')
            ->pluck('count', 'subscription_status')
            ->all();

        $activeClinics = (int) ($clinicsCountByStatus['active'] ?? 0);
        $trialClinics = (int) ($clinicsCountByStatus['trial'] ?? 0);
        $delinquentClinics = (int) ($clinicsCountByStatus['past_due'] ?? 0);
        $canceledClinics = (int) ($clinicsCountByStatus['canceled'] ?? 0);
        $totalClinics = array_sum($clinicsCountByStatus);

        // MRR is sum of plan prices for active clinics
        $mrr = (float) Clinic::query()
            ->where('clinics.subscription_status', 'active')
            ->join('plans', 'clinics.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        $totalAppointments = Appointment::withoutGlobalScopes()->count();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthAppointments = Appointment::withoutGlobalScopes()
            ->whereBetween('scheduled_at', [$startOfMonth, $endOfMonth])
            ->count();

        $recentClinics = Clinic::query()
            ->with('plan')
            ->latest()
            ->limit(5)
            ->get();

        return [
            'total_clinics' => $totalClinics,
            'active_clinics' => $activeClinics,
            'trial_clinics' => $trialClinics,
            'delinquent_clinics' => $delinquentClinics,
            'canceled_clinics' => $canceledClinics,
            'mrr' => $mrr,
            'total_appointments' => $totalAppointments,
            'month_appointments' => $monthAppointments,
            'recent_clinics' => $recentClinics,
        ];
    }
}
