<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_lossreasons extends Model {

    //Table Name
    protected $table = 'tbl_lossreasons';
    //Primary key
    public $primaryKey = 'lr_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lr_id', 'reason'];

    public function tbl_deals() {
        return $this->hasMany('App\Tbl_deals', 'lr_id');
    }

}
