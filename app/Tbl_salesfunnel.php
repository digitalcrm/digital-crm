<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_salesfunnel extends Model {

    //Table Name
    protected $table = 'tbl_salesfunnel';
    //Primary key
    public $primaryKey = 'sfun_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'salesfunnel', 'color'
    ];

    public function tbl_deals() {
        return $this->hasMany('App\Tbl_deals', 'sfun_id');
    }

}
