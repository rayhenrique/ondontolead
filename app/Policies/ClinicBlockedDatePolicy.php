<?php

namespace App\Policies;

use App\Models\ClinicBlockedDate;
use App\Models\User;
use App\Policies\Concerns\HandlesSuperAdminAuthorization;

class ClinicBlockedDatePolicy
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
    public function view(User $user, ClinicBlockedDate $clinicBlockedDate): bool
    {
        return $this->belongsToTenant($user, $clinicBlockedDate->clinic_id);
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
    public function update(User $user, ClinicBlockedDate $clinicBlockedDate): bool
    {
        return $this->belongsToTenant($user, $clinicBlockedDate->clinic_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClinicBlockedDate $clinicBlockedDate): bool
    {
        return $this->belongsToTenant($user, $clinicBlockedDate->clinic_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClinicBlockedDate $clinicBlockedDate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClinicBlockedDate $clinicBlockedDate): bool
    {
        return false;
    }
}
