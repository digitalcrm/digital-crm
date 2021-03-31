<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_deliveryby extends Model
{
    //Table Name
    protected $table = 'tbl_deliveryby';
    //Primary key
    public $primaryKey = 'dlb_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dlb_id',
        'delivery_by'
    ];

    public function tbl_orders()
    {
        return $this->hasMany('App\Tbl_orders', 'dlb_id');
    }
}
