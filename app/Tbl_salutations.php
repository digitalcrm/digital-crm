<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_salutations extends Model {

    //Table Name
    protected $table = 'tbl_salutations';
    //Primary key
    public $primaryKey = 'sal_id';
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

    public function tbl_leads() {
        return $this->hasMany('App\Tbl_leads', 'sal_id');
    }

    public function tbl_contacts() {
        return $this->hasMany('App\Tbl_contacts', 'sal_id');
    }

}
