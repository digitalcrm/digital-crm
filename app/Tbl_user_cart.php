<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_user_cart extends Model
{

    //Table Name
    protected $table = 'tbl_user_cart';
    //Primary key
    public $primaryKey = 'uc_id';
    //Timestamps
    public $timestamps = true;

    protected $fillable = [
        'uc_id',
        'uid',
        'pro_id',
        'status',
        'quantity'
    ];

    public function consumers()
    {
        return $this->belongsTo('App\Consumer', 'uid');
    }

    public function tbl_products()
    {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }
}
