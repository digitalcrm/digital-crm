<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_tax extends Model
{

    //Table Name
    protected $table = 'tbl_tax';
    //Primary key
    public $primaryKey = 'tax_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tax_id', 'uid', 'name', 'tax', 'active'];

    public function users()
    {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_invoice_products()
    {
        return $this->hasMany('App\Tbl_invoice_products', 'tax_id');
    }
}
