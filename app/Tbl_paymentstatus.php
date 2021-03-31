<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_paymentstatus extends Model
{
    //Table Name
    protected $table = 'tbl_paymentstatus';
    //Primary key
    public $primaryKey = 'paystatus_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['paystatus_id', 'status'];    //event

    public function tbl_deals()
    {
        return $this->hasMany('App\Tbl_deals', 'paystatus_id');
    }
}
