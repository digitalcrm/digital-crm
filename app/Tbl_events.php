<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_events extends Model {

    //Table Name
    protected $table = 'tbl_events';
    //Primary key
    public $primaryKey = 'ev_id';
    //Timestamps
    public $timestamps = true;
    //Fillable columns
    protected $fillable = ['ev_id', 'uid', 'title', 'description', 'start_date', 'end_date', 'start_time', 'end_time', 'startDatetime', 'endDatetime', 'type', 'id', 'active', 'allday', 'location', 'evtype_id', 'cron_status'];

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_event_types() {
        return $this->belongsTo('App\Tbl_event_types', 'evtype_id');
    }

}
