<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_territory_users extends Model {

    //Table Name
    protected $table = 'tbl_territory_users';
    //Primary key
    public $primaryKey = 'tuid';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tuid', 'uid', 'tid', 'suid'];

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_territory() {
        return $this->belongsTo('App\Tbl_territory', 'tid');
    }
//
//    public function subusers() {
//        return $this->belongsTo('App\user', 'uid');
//    }

}
