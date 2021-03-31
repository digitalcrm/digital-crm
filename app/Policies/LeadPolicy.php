<?php

namespace App\Policies;

use App\Tbl_leads;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LeadPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\LeadPolicy  $leadPolicy
     * @return mixed
     */
    public function view(User $user, Tbl_leads $lead)
    {
        //
        return $user->id === $lead->uid
            ? Response::allow()
            : Response::deny('Forbidden.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\LeadPolicy  $leadPolicy
     * @return mixed
     */
    public function update(User $user, Tbl_leads $lead)
    {
        //
        return $this->view($user, $lead);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\LeadPolicy  $leadPolicy
     * @return mixed
     */
    public function delete(User $user, Tbl_leads $lead)
    {
        //
        return $this->view($user, $lead);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\LeadPolicy  $leadPolicy
     * @return mixed
     */
    public function restore(User $user, LeadPolicy $leadPolicy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\LeadPolicy  $leadPolicy
     * @return mixed
     */
    public function forceDelete(User $user, LeadPolicy $leadPolicy)
    {
        //
    }
}
