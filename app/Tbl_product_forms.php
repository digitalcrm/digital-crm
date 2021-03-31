<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_product_forms extends Model {

    //Table Name
    protected $table = 'tbl_products_forms';
    //Primary key
    public $primaryKey = 'pf_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_id',
        'form_id'
    ];

    public function tbl_fb_leads() {
        return $this->belongsTo('App\Tbl_fb_leads', 'form_id');
    }

    public function tbl_products() {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

}
