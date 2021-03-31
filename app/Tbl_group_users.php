<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_group_users extends Model {

    //Table Name
    protected $table = 'tbl_group_users';
    //Primary key
    public $primaryKey = 'gu_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gid',
        'uid',
        'quota',
    ];

    public function tbl_groups() {
        return $this->belongsTo('App\Tbl_groups', 'gid');
    }

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

}
