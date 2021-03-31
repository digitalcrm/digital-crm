<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ClientResetpasswordNotification;

class Client extends Authenticatable
{

    use Notifiable;
    // use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'picture', 'user_type', 'cr_id', 'active', 'verified', 'user_status', 'mobile', 'country', 'state', 'city', 'zip', 'address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ClientResetpasswordNotification($token));
    }

    public function currency()
    {
        return $this->belongsTo('App\currency', 'cr_id');
    }

    public function tbl_verifyclient()
    {
        return $this->hasOne('App\Tbl_verifyclient', 'cl_id');
    }
    
    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state');
    }
}
