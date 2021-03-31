<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_territory extends Model {

    //Table Name
    protected $table = 'tbl_territory';
    //Primary key
    public $primaryKey = 'tid';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tid', 'name', 'uid', 'latitude', 'longitude', 'description', 'subusers', 'active'];

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_territory_users() {
        return $this->hasMany('App\Tbl_territory_users', 'tid');
    }

}
