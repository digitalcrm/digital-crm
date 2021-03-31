<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_groups extends Model {

    //Table Name
    protected $table = 'tbl_groups';
    //Primary key
    public $primaryKey = 'gid';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gid',
        'name',
        'uid',
        'active',
        'description',
        'users',
        'products',
    ];

    public function tbl_group_users() {
        return $this->hasMany('App\Tbl_group_users', 'gid');
    }

    public function tbl_group_products() {
        return $this->hasMany('App\Tbl_group_products', 'gid');
    }

}
