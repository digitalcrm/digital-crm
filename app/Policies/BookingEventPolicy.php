<?php

namespace App\Policies;

use App\User;
use App\BookingEvent;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingEventPolicy
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
     * @param  \App\BookingEvent  $bookevent
     * @return mixed
     */
    public function view(User $user, BookingEvent $bookevent)
    {
        return $user->id === $bookevent->user_id
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
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\BookingEvent  $bookevent
     * @return mixed
     */
    public function update(User $user, BookingEvent $bookevent)
    {
        return $this->view($user, $bookevent);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BookingEvent  $bookevent
     * @return mixed
     */
    public function delete(User $user, BookingEvent $bookevent)
    {
        return $this->view($user, $bookevent);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\BookingEvent  $bookevent
     * @return mixed
     */
    // public function restore(User $user, BookingEvent $bookevent)
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BookingEvent  $bookevent
     * @return mixed
     */
    // public function forceDelete(User $user, BookingEvent $bookevent)
    // {
    //     //
    // }
}
