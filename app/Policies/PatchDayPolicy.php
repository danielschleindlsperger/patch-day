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
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the patch-day index resource.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isAdmin();
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
     * @param  \App\PatchDay $patchDay
     * @return mixed
     */
    public function update(User $user, PatchDay $patchDay)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the patchDay.
     *
     * @param  \App\User $user
     * @param  \App\PatchDay $patchDay
     * @return mixed
     */
    public function delete(User $user, PatchDay $patchDay)
    {
        return $user->isAdmin();
    }
}
