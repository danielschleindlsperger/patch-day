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
     * @return mixed
     * @param  \App\Protocol $protocol
     */
    public function view(User $user, Protocol $protocol)
    {
        $userCompany = $user->company;
        $protocolCompany = $protocol->project->company;

        return $userCompany && $protocolCompany &&
            $userCompany->id === $protocolCompany->id;
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
        $protocolCompany = $protocol->project->company;
        $userCompany = $user->company;

        return $protocolCompany && $userCompany &&
            $protocolCompany->id === $userCompany->id;
    }
}
