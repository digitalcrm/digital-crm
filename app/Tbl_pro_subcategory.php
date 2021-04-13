<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_pro_subcategory extends Model
{
    use HasFactory;

    //Table Name
    protected $table = 'tbl_pro_subcategory';

    //Primary key
    // public $primaryKey = 'prosubcat_id';

    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pro_id', 'prosubcat_id'];

    public function tbl_products()
    {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

    public function tbl_product_subcategory()
    {
        return $this->belongsTo('App\Tbl_product_subcategory', 'prosubcat_id');
    }
}
