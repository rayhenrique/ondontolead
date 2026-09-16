<?php

namespace App\Http\Middleware;

use App\Models\AppRelease;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckUnreadReleases
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $release = AppRelease::query()
            ->where('show_modal', true)
            ->where('released_at', '<=', now())
            ->whereDoesntHave(
                'userReleaseReads',
                fn (Builder $query): Builder => $query->where('user_id', $user->getKey()),
            )
            ->latest('released_at')
            ->latest('id')
            ->first();

        if ($release === null) {
            $request->session()->forget('unread_app_release_id');
        } else {
            $request->session()->put('unread_app_release_id', $release->getKey());
        }

        View::share('unreadAppRelease', $release);

        return $next($request);
    }
}
