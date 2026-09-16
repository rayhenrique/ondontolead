<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

final class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (self::isSuperAdmin()) {
            return;
        }

        $tenantId = self::tenantId();

        if ($tenantId === null) {
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->where($model->qualifyColumn('clinic_id'), $tenantId);
    }

    public static function tenantId(): ?int
    {
        $user = Auth::user();

        if ($user instanceof User) {
            return $user->is_superadmin ? null : $user->clinic_id;
        }

        if (! app()->bound('session')) {
            return null;
        }

        $tenantId = session('tenant_id');

        if (is_int($tenantId) && $tenantId > 0) {
            return $tenantId;
        }

        if (is_string($tenantId) && ctype_digit($tenantId) && (int) $tenantId > 0) {
            return (int) $tenantId;
        }

        return null;
    }

    public static function isSuperAdmin(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->is_superadmin;
    }
}
