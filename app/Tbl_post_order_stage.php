<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_post_order_stage extends Model
{

    //Table Name
    protected $table = 'tbl_post_order_stage';
    //Primary key
    public $primaryKey = 'pos_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_id',
        'stage'
    ];

    public function tbl_deals()
    {
        return $this->hasMany('App\Tbl_deals', 'pos_id');
    }

    public function tbl_cart_orders()
    {
        return $this->hasMany('App\Tbl_cart_orders', 'pos_id');
    }

    public function tbl_invoice()
    {
        return $this->hasMany('App\Tbl_invoice', 'pos_id');
    }
}
