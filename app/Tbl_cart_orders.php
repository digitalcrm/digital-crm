<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_cart_orders extends Model
{
    //Table Name
    protected $table = 'tbl_cart_orders';
    //Primary key
    public $primaryKey = 'coid';
    //Timestamps
    public $timestamps = true;

    protected $fillable = [
        'coid',
        'uid',
        'user_type',
        'name',
        'email',
        'mobile',
        'pro_id',
        'order_date',
        'shipping_date',
        'delivery_charges',
        'total_amount',
        'quantity',
        'price',
        'remarks',
        'address',
        'country',
        'state',
        'city',
        'zip',
        'number',
        'pos_id',
        'active',
        'message',
        'ld_id'
    ];

    public function consumers()
    {
        return $this->belongsTo('App\Consumer', 'uid');
    }

    public function tbl_products()
    {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state');
    }

    public function tbl_post_order_stage()
    {
        return $this->belongsTo('App\Tbl_post_order_stage', 'pos_id');
    }

    public function tbl_leads()
    {
        return $this->belongsTo('App\Tbl_leads', 'ld_id');
    }
}
