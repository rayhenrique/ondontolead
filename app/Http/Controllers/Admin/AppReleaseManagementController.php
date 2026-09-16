<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppReleaseRequest;
use App\Http\Requests\Admin\UpdateAppReleaseRequest;
use App\Models\AppRelease;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AppReleaseManagementController extends Controller
{
    public function index(): View
    {
        $releases = AppRelease::query()
            ->withCount('userReleaseReads')
            ->orderByDesc('released_at')
            ->paginate(15);

        return view('admin.releases.index', compact('releases'));
    }

    public function create(): View
    {
        return view('admin.releases.create');
    }

    public function store(StoreAppReleaseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['show_modal'] = $request->boolean('show_modal', true);

        AppRelease::query()->create($data);

        return redirect()
            ->route('admin.releases.index')
            ->with('status', 'Nova release publicada com sucesso.');
    }

    public function edit(AppRelease $release): View
    {
        return view('admin.releases.edit', compact('release'));
    }

    public function update(UpdateAppReleaseRequest $request, AppRelease $release): RedirectResponse
    {
        $data = $request->validated();
        $data['show_modal'] = $request->boolean('show_modal', true);

        $release->update($data);

        return redirect()
            ->route('admin.releases.index')
            ->with('status', 'Release atualizada com sucesso.');
    }

    public function destroy(AppRelease $release): RedirectResponse
    {
        $release->delete();

        return redirect()
            ->route('admin.releases.index')
            ->with('status', 'Release excluída com sucesso.');
    }
}
