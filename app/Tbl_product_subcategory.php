<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_product_subcategory extends Model
{
    use HasFactory;

    //Table Name
    protected $table = 'tbl_product_subcategory';
    //Primary key
    public $primaryKey = 'prosubcat_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['prosubcat_id', 'procat_id', 'category','slug'];

    public function tbl_products()
    {
        return $this->hasMany('App\Tbl_products', 'prosubcat_id');
    }

    public function tbl_product_category()
    {
        return $this->belongsTo('App\Tbl_productcategory', 'procat_id');
    }
}
