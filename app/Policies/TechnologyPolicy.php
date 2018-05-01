<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnologyPolicy
{
    use HandlesAuthorization;

    /**
     * Admins can do anything.
     *
     * @param User $user
     * @return bool|null
     */
    public function before(User $user)
    {
        return $user->isAdmin() ?: null;
    }

    /**
     * Determine whether the user can view the technology index resource.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the technology.
     * @return mixed
     * @internal param User $user
     * @internal param Technology $technology
     */
    public function view(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create technologies.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the technology.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Technology $technology
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the technology.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Technology $technology
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
