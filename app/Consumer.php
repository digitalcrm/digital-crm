<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Consumer extends Authenticatable
{
    //
    protected $fillable = [
        'name', 'email', 'password', 'picture', 'user_type', 'cr_id', 'active', 'verified', 'user_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function tbl_user_cart()
    {
        return $this->hasMany('App\Tbl_user_cart', 'id');
    }

    public function tbl_cart_orders()
    {
        return $this->hasMany('App\Tbl_cart_orders', 'id');
    }
}
