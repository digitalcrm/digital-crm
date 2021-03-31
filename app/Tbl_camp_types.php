<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_camp_types extends Model {

    //Table Name
    protected $table = 'tbl_camp_type';
    //Primary key
    public $primaryKey = 'type_id';
    //Timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'type',
    ];

    public function tbl_campaigns() {
        return $this->hasMany('App\Tbl_campaigns', 'type_id');
    }

}
