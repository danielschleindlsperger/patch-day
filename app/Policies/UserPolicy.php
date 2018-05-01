<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Admins can do anything.
     *
     * @param User $requestee
     * @return bool|null
     */
    public function before(User $requestee)
    {
        return $requestee->isAdmin() ?: null;
    }

    /**
     * Determine whether the user can view the user index.
     * @return bool
     * @internal param User $requestee
     * @internal param User $user
     */
    public function index(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\User $requestee
     * @param  \App\User $user
     * @return bool
     */
    public function view(User $requestee, User $user): bool
    {
        return $requestee->id === $user->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User $requestee
     * @return bool
     */
    public function create(User $requestee): bool
    {
        return $requestee->isAdmin();
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User $requestee
     * @param  \App\User $user
     * @return bool
     */
    public function update(User $requestee, User $user): bool
    {
        return $requestee->id === $user->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User $requestee
     * @return mixed
     */
    public function delete(User $requestee): bool
    {
        return $requestee->isAdmin();
    }
}
