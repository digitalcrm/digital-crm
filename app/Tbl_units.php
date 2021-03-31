<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_units extends Model {

    //Table Name
    protected $table = 'tbl_units';
    //Primary key
    public $primaryKey = 'unit_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['unit_id', 'name', 'sortname'];

    public function tbl_product() {
        return $this->hasMany('App\Tbl_products', 'unit_id');
    }

}
