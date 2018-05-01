<?php

namespace App\Policies;

use App\User;
use App\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
     * Determine whether the user can view the company index resource.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param  \App\User $user
     * @param  \App\Company $company
     * @return mixed
     */
    public function view(User $user, Company $company): bool
    {
        if ($user->company) {
            return $user->company->id === $company->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Company $company
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
