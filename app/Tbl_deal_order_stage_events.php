<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_deal_order_stage_events extends Model
{
    //Table Name
    protected $table = 'tbl_deal_order_stage_events';
    //Primary key
    public $primaryKey = 'dos_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'dos_id', 'oid', 'os_from', 'os_to', 'os_time'
    ];

    public function tbl_orders()
    {
        return $this->belongsTo('App\Tbl_orders', 'oid');
    }
}
