<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_event_types extends Model {

    //Table Name
    protected $table = 'tbl_event_types';
    //Primary key
    public $primaryKey = 'evtype_id';
    //Timestamps
    public $timestamps = true;
    //Fillable columns
    protected $fillable = ['evtype_id', 'type'];

    public function tbl_events() {
        return $this->belongsTo('App\Tbl_events', 'evtype_id');
    }

}
