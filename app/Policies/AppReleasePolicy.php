<?php

namespace App\Policies;

use App\Models\AppRelease;
use App\Models\User;
use App\Policies\Concerns\HandlesSuperAdminAuthorization;

class AppReleasePolicy
{
    use HandlesSuperAdminAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppRelease $appRelease): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppRelease $appRelease): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppRelease $appRelease): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppRelease $appRelease): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppRelease $appRelease): bool
    {
        return false;
    }
}
