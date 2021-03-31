<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class currency extends Model
{
    //Table Name
    protected $table = 'currency';
    //Primary key
    public $primaryKey = 'cr_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'status', 'html_code','active'];

    public function users()
    {
        return $this->hasMany('App\User', 'cr_id');
    }


    public function admins()
    {
        return $this->hasMany('App\Admin', 'cr_id');
    }

    public function clients()
    {
        return $this->hasMany('App\Client', 'cr_id');
    }

}
