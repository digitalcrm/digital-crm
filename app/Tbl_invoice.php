<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_invoice extends Model
{

    //Table Name
    protected $table = 'tbl_invoice';
    //Primary key
    public $primaryKey = 'inv_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'uid', 'name', 'billto', 'billto_address', 'cmp_logo', 'subtotal', 'discount', 'subtotal_discount',
        'shipping', 'total_amount', 'status', 'ld_id', 'inv_number', 'inv_date', 'active', 'notes', 'pos_id'
    ];

    public function users()
    {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_leads()
    {
        return $this->belongsTo('App\Tbl_leads', 'ld_id');
    }

    public function tbl_invoice_products()
    {
        return $this->hasMany('App\Tbl_invoice_products', 'inv_id');
    }

    public function tbl_post_order_stage()
    {
        return $this->belongsTo('App\Tbl_post_order_stage', 'pos_id');
    }
}
