<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\AppRelease;
use App\Models\User;
use App\Models\UserReleaseRead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClinicReleaseController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $releases = AppRelease::query()
            ->where('released_at', '<=', now())
            ->with(['userReleaseReads' => function ($query) use ($user): void {
                $query->where('user_id', $user->id);
            }])
            ->orderByDesc('released_at')
            ->paginate(10);

        return view('clinic.releases.index', compact('releases'));
    }

    public function markAsRead(Request $request, AppRelease $release): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        UserReleaseRead::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'app_release_id' => $release->id,
            ],
            [
                'read_at' => Carbon::now(),
            ]
        );

        if ($request->session()->get('unread_app_release_id') === $release->id) {
            $request->session()->forget('unread_app_release_id');
        }

        return redirect()->back()->with('status', "Release {$release->version} marcada como lida.");
    }
}
