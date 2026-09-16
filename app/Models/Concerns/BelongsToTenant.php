<?php

namespace App\Models\Concerns;

use App\Models\Clinic;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::saving(function (Model $model): void {
            if (TenantScope::isSuperAdmin()) {
                return;
            }

            $tenantId = TenantScope::tenantId();

            if ($tenantId !== null) {
                $model->setAttribute('clinic_id', $tenantId);
            }
        });
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
