<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->is_superadmin === true) {
            return $next($request);
        }

        $clinic = $user?->clinic;
        $hasAccess = $clinic?->subscription_status === 'active'
            || ($clinic?->subscription_status === 'trial'
                && ($clinic->trial_ends_at === null || $clinic->trial_ends_at->isFuture()));

        abort_unless($hasAccess, Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
