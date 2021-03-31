<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_invoice_products extends Model {

    //Table Name
    protected $table = 'tbl_invoice_products';
    //Primary key
    public $primaryKey = 'inv_pro_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'inv_id', 'pro_id', 'quantity', 'tax_id',
    ];

    public function tbl_invoice() {
        return $this->belongsTo('App\Tbl_invoice', 'inv_id');
    }

    public function tbl_products() {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

    public function tbl_tax() {
        return $this->belongsTo('App\Tbl_tax', 'tax_id');
    }

}
