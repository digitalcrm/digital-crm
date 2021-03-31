<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_camp_status extends Model {

    //Table Name
    protected $table = 'tbl_camp_status';
    //Primary key
    public $primaryKey = 'status_id';
    //Timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_id',
        'status',
    ];

    public function tbl_campaigns() {
        return $this->belongsTo('App\Tbl_campaigns', 'status_id');
    }

}
