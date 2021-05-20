<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetpasswordNotification;
use App\Traits\DefaultProfile;

// use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, DefaultProfile;
    // use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    protected $appends = ['profile_img'];

    public function getProfileImgAttribute()
    {
        return $this->profileLogo();
    }

    public function profileLogo()
    {
        return $this->picture ? asset($this->picture) : $this->defaultProfilePhotoUrl($this->name);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetpasswordNotification($token));
    }

    public function tbl_admin_mails()
    {
        return $this->hasMany('App\Tbl_admin_mails', 'id');
    }

    public function tbl_products()
    {
        return $this->hasMany('App\Tbl_products', 'uid');
    }

    public function tbl_admin_permissions()
    {
        return $this->hasMany('App\Tbl_Admin_Permissions', 'admin_id');
    }

    public function tbl_verifyadmin()
    {
        return $this->hasMany('App\Tbl_Verifyadmin', 'id');
    }

    public function currentadmin()
    {
        return $this->hasMany('App\Todo', 'admin_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\currency', 'cr_id');
    }

    public function company()
    {
        return $this->hasMany('App\Company', 'admin_id');
    }
}
