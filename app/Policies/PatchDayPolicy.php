<?php

namespace App\Policies;

use App\User;
use App\PatchDay;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatchDayPolicy
{
    use HandlesAuthorization;

    /**
     * Admins can do anything.
     *
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the patchDay.
     *
     * @param  \App\User $user
     * @param  \App\PatchDay $patchDay
     * @return mixed
     */
    public function view(User $user, PatchDay $patchDay)
    {
        if ($user->company) {
            return $user->company->id === $patchDay->project->company->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create patchDays.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the patchDay.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param PatchDay $patchDay
     */
    public function update(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the patchDay.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param PatchDay $patchDay
     */
    public function delete(User $user)
    {
        return $user->isAdmin();
    }
}
