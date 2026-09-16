<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PlanManagementController extends Controller
{
    public function index(): View
    {
        $plans = Plan::query()
            ->withCount('clinics')
            ->orderByDesc('is_active')
            ->orderBy('price')
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.plans.create');
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Plan::query()->create($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('status', 'Plano criado com sucesso.');
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $plan->update($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('status', 'Plano atualizado com sucesso.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->clinics()->exists()) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Não é possível excluir este plano pois existem clínicas vinculadas a ele. Você pode desativá-lo para impedir novas adesões.']);
        }

        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('status', 'Plano removido com sucesso.');
    }
}
