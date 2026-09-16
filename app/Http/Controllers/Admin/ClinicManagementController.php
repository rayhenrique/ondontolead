<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClinicRequest;
use App\Http\Requests\Admin\UpdateClinicRequest;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClinicManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();
        $planId = $request->integer('plan_id');

        $clinics = Clinic::query()
            ->with('plan')
            ->withCount(['appointments', 'users'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($sub) use ($search): void {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('whatsapp_number', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('subscription_status', $status);
            })
            ->when($planId > 0, function ($query) use ($planId): void {
                $query->where('plan_id', $planId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $plans = Plan::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.clinics.index', compact('clinics', 'plans', 'search', 'status', 'planId'));
    }

    public function create(): View
    {
        $plans = Plan::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.clinics.create', compact('plans'));
    }

    public function store(StoreClinicRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            $trialDays = isset($validated['trial_days']) && $validated['trial_days'] !== null
                ? (int) $validated['trial_days']
                : null;

            $trialEndsAt = $trialDays !== null
                ? Carbon::now()->addDays($trialDays)
                : null;

            $clinic = Clinic::query()->create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'whatsapp_number' => $validated['whatsapp_number'],
                'plan_id' => $validated['plan_id'],
                'subscription_status' => $validated['subscription_status'],
                'trial_ends_at' => $trialEndsAt,
                'ai_provider' => 'none',
            ]);

            User::query()->create([
                'clinic_id' => $clinic->id,
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['user_password']),
                'is_superadmin' => false,
            ]);
        });

        return redirect()
            ->route('admin.clinics.index')
            ->with('status', 'Clínica e usuário gestor criados com sucesso.');
    }

    public function edit(Clinic $clinic): View
    {
        $clinic->load('plan');
        $plans = Plan::query()->orderBy('name')->get();

        return view('admin.clinics.edit', compact('clinic', 'plans'));
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validated();

        $trialEndsAt = $clinic->trial_ends_at;

        if (! empty($validated['trial_ends_at'])) {
            $trialEndsAt = Carbon::parse($validated['trial_ends_at']);
        }

        if (! empty($validated['extend_trial_days'])) {
            $base = ($trialEndsAt !== null && $trialEndsAt->isFuture()) ? $trialEndsAt : Carbon::now();
            $trialEndsAt = $base->addDays((int) $validated['extend_trial_days']);
        }

        $clinic->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'plan_id' => $validated['plan_id'],
            'subscription_status' => $validated['subscription_status'],
            'trial_ends_at' => $trialEndsAt,
        ]);

        return redirect()
            ->route('admin.clinics.index')
            ->with('status', 'Clínica atualizada com sucesso.');
    }

    public function updateStatus(Request $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'past_due', 'canceled'])],
        ]);

        $clinic->update([
            'subscription_status' => $validated['subscription_status'],
        ]);

        return redirect()
            ->back()
            ->with('status', "Status da clínica atualizado para '{$clinic->subscription_status}'.");
    }
}
