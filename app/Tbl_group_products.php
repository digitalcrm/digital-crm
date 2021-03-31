<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_group_products extends Model {

    //Table Name
    protected $table = 'tbl_group_products';
    //Primary key
    public $primaryKey = 'gp_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gid',
        'pro_id',
    ];

    public function tbl_groups() {
        return $this->belongsTo('App\Tbl_groups', 'gid');
    }

    public function tbl_products() {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

}
