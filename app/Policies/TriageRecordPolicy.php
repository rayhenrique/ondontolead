<?php

namespace App\Policies;

use App\Models\TriageRecord;
use App\Models\User;
use App\Policies\Concerns\HandlesSuperAdminAuthorization;

class TriageRecordPolicy
{
    use HandlesSuperAdminAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->clinic_id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TriageRecord $triageRecord): bool
    {
        return $this->belongsToTenant($user, $this->clinicId($triageRecord));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->clinic_id !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TriageRecord $triageRecord): bool
    {
        return $this->belongsToTenant($user, $this->clinicId($triageRecord));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TriageRecord $triageRecord): bool
    {
        return $this->belongsToTenant($user, $this->clinicId($triageRecord));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TriageRecord $triageRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TriageRecord $triageRecord): bool
    {
        return false;
    }

    private function clinicId(TriageRecord $triageRecord): ?int
    {
        $clinicId = $triageRecord->appointment()
            ->withoutGlobalScopes()
            ->value('clinic_id');

        return $clinicId === null ? null : (int) $clinicId;
    }
}
