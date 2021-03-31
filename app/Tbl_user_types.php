<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_user_types extends Model
{

    //Table Name
    protected $table = 'tbl_user_types';
    //Primary key
    public $primaryKey = 'ut_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ut_id', 'type', 'weight'];

    public function users()
    {
        return $this->hasMany('App\User', 'ut_id');
    }
}
