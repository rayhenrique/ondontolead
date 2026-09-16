<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdminMetricsService;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(SuperAdminMetricsService $metricsService): View
    {
        $metrics = $metricsService->getMetrics();

        return view('admin.dashboard', compact('metrics'));
    }
}
