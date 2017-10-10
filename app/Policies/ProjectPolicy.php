<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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
     * Determine whether the user can view the project index resource.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User $user
     * @param  \App\Project $project
     * @return bool
     */
    public function view(User $user, Project $project)
    {
        if ($user->company && $project->company) {
            return $user->company->id === $project->company->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Project $project
     */
    public function update(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Project $project
     */
    public function delete(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can signup the project to patch-days.
     *
     * @param  \App\User $user
     * @param  \App\Project $project
     * @return mixed
     */
    public function signup(User $user, Project $project)
    {
        return $user->company && $project->company &&
            $user->company->id === $project->company->id;
    }

    /**
     * Determine whether the user can delete technologies from projects.
     *
     * @param  \App\User $user
     * @return mixed
     * @internal param Project $project
     */
    public function deleteProjectTech(User $user)
    {
        return $user->isAdmin();
    }
}
