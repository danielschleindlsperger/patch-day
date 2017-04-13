<?php

namespace App\Policies;

use App\Company;
use App\User;
use App\Protocol;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProtocolPolicy
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
     * Determine whether the user can view the protocol.
     *
     * @param  \App\User $user
     * @param  \App\Protocol $protocol
     * @return mixed
     */
    public function view(User $user, Protocol $protocol)
    {
        try {
            $company = $protocol->patchDay->project->company;
            if ($user->company && $company) {
                return $user->company->id === $company->id;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Determine whether the user can create protocols.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the protocol.
     *
     * @param  \App\User $user
     * @param  \App\Protocol $protocol
     * @return mixed
     */
    public function update(User $user, Protocol $protocol)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the protocol.
     *
     * @param  \App\User $user
     * @param  \App\Protocol $protocol
     * @return mixed
     */
    public function delete(User $user, Protocol $protocol)
    {
        return $user->isAdmin();
    }
}
