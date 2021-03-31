<?php

namespace App\Policies;

use App\Tbl_orders;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
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
     * @param  \App\Tbl_orders  $tblOrders
     * @return mixed
     */
    public function view(User $user, Tbl_orders $order)
    {
        //
        return $user->id === $order->uid
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
     * @param  \App\Tbl_orders  $tblOrders
     * @return mixed
     */
    public function update(User $user, Tbl_orders $order)
    {
        //
        return $this->view($user, $order);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tbl_orders  $tblOrders
     * @return mixed
     */
    public function delete(User $user, Tbl_orders $order)
    {
        //
        return $this->view($user, $order);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tbl_orders  $tblOrders
     * @return mixed
     */
    public function restore(User $user, Tbl_orders $tblOrders)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tbl_orders  $tblOrders
     * @return mixed
     */
    public function forceDelete(User $user, Tbl_orders $tblOrders)
    {
        //
    }
}
