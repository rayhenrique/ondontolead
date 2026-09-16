<?php

namespace App\Policies;

use App\Models\ClinicSchedule;
use App\Models\User;
use App\Policies\Concerns\HandlesSuperAdminAuthorization;

class ClinicSchedulePolicy
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
    public function view(User $user, ClinicSchedule $clinicSchedule): bool
    {
        return $this->belongsToTenant($user, $clinicSchedule->clinic_id);
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
    public function update(User $user, ClinicSchedule $clinicSchedule): bool
    {
        return $this->belongsToTenant($user, $clinicSchedule->clinic_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClinicSchedule $clinicSchedule): bool
    {
        return $this->belongsToTenant($user, $clinicSchedule->clinic_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClinicSchedule $clinicSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClinicSchedule $clinicSchedule): bool
    {
        return false;
    }
}
