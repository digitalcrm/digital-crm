<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_orders extends Model
{

    //Table Name
    protected $table = 'tbl_orders';
    //Primary key
    public $primaryKey = 'oid';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'oid', 'uid', 'deal_id', 'number', 'order_date', 'pos_id', 'shipping_date', 'delivery_charges', 'total_amount', 'quantity', 'remarks', 'dlb_id', 'verify_by'
    ];

    public function tbl_post_order_stage()
    {
        return $this->belongsTo('App\Tbl_post_order_stage', 'pos_id');
    }

    public function tbl_deals()
    {
        return $this->belongsTo('App\Tbl_deals', 'deal_id');
    }

    public function tbl_deliveryby()
    {
        return $this->belongsTo('App\Tbl_deliveryby', 'dlb_id');
    }

    public function tbl_deal_order_stage_events()
    {
        return $this->hasMany('App\Tbl_deal_order_stage_events', 'oid');
    }
}
