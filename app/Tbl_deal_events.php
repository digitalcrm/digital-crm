<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_deal_events extends Model {

    //Table Name
    protected $table = 'tbl_deal_events';
    //Primary key
    public $primaryKey = 'dev_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'dev_id', 'deal_status', 'deal_id', 'sfun_from', 'sfun_to', 'event_time'
    ];

    public function tbl_deals() {
        return $this->belongsTo('App\Tbl_deals', 'deal_id');
    }

}
