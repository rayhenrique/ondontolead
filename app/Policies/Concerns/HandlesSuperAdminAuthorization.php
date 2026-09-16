<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait HandlesSuperAdminAuthorization
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->is_superadmin ? true : null;
    }

    protected function belongsToTenant(User $user, ?int $clinicId): bool
    {
        return $user->clinic_id !== null && $user->clinic_id === $clinicId;
    }
}
