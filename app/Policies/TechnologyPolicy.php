<?php

namespace App\Policies;

use App\User;
use App\Technology;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnologyPolicy
{
    use HandlesAuthorization;

    /**
     * Admins can do anything.
     *
     * @param $user
     * @return bool
     */
    public function before($user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the technology index resource.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the technology.
     *
     * @param  \App\User  $user
     * @param  \App\Technology  $technology
     * @return mixed
     */
    public function view(User $user, Technology $technology)
    {
        return true;
    }

    /**
     * Determine whether the user can create technologies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the technology.
     *
     * @param  \App\User  $user
     * @param  \App\Technology  $technology
     * @return mixed
     */
    public function update(User $user, Technology $technology)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the technology.
     *
     * @param  \App\User  $user
     * @param  \App\Technology  $technology
     * @return mixed
     */
    public function delete(User $user, Technology $technology)
    {
        return $user->isAdmin();
    }
}
