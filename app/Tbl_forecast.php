<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_forecast extends Model {

    //Table Name
    protected $table = 'tbl_forecast';
    //Primary key
    public $primaryKey = 'fcid';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'month',
        'year',
        'forecast',
        'achieved',
    ];

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

}
