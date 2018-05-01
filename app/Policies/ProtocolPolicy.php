<?php

namespace App\Policies;

use App\Protocol;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProtocolPolicy
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
     * Determine whether the user can view the protocol.
     *
     * @param  \App\User     $user
     * @return mixed
     * @param  \App\Protocol $protocol
     */
    public function view(User $user, Protocol $protocol): bool
    {
        $userCompany     = $user->company;
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
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the protocol.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Protocol $protocol
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the protocol.
     *
     * @param  \App\User     $user
     * @param  \App\Protocol $protocol
     * @return mixed
     */
    public function delete(User $user, Protocol $protocol): bool
    {
        $protocolCompany = $protocol->project->company;
        $userCompany     = $user->company;

        return $protocolCompany && $userCompany &&
            $protocolCompany->id === $userCompany->id;
    }
}
