<?php

namespace VentureDrake\LaravelCrm\Policies;

use App\User;
use App\Models\JobModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermissionTo('view crm export-import')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\JobModel  $model
     * @return mixed
     */
    public function view(User $user, JobModel $updatingUser)
    {
        if ($user->hasPermissionTo('view crm export-import')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo('create crm export-import')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, JobModel $updatingUser)
    {
        if ($user->hasPermissionTo('edit crm export-import')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, JobModel $updatingUser)
    {
        if ($user->hasPermissionTo('delete crm export-import')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, JobModel $updatingUser)
    {
        if ($user->hasPermissionTo('delete crm export-import')) {
            return true;
        }
    }

    public function export(User $user, JobModel $updatingUser)
    {
        if ($user->hasPermissionTo('export crm export-import')) {
            return true;
        }
    }

    public function import(User $user, JobModel $updatingUser)
    {
        if ($user->hasPermissionTo('import crm export-import')) {
            return true;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, JobModel $updatingUser)
    {
        return false;
    }
}
