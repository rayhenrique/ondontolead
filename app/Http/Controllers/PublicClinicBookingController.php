<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicClinicBookingController extends Controller
{
    public function __invoke(Request $request, string $slug): View
    {
        $clinic = Clinic::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $isPaused = in_array($clinic->subscription_status, ['past_due', 'canceled'], true);

        return view('clinic.public.landing', compact('clinic', 'isPaused'));
    }
}
