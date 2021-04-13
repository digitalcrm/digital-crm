<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_products extends Model
{

    //Table Name
    protected $table = 'tbl_products';
    //Primary key
    public $primaryKey = 'pro_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pro_id', 'name', 'picture', 'description', 'size', 'price', 'unit', 'uid', 'views', 'active', 'procat_id', 'enable', 'user_type', 'store', 'prosubcat_id', 'vendor', 'tags', 'slideshowpics', 'slug', 'featured', 'productid', 'productsku', 'qrcode', 'supply_price', 'current_stock', 'company', 'location', 'stock', 'min_quantity'];

    protected $appends = ['product_img'];

    public function getProductImgAttribute()
    {
        return $this->productLogo();
    }

    public function productLogo()
    {
        return $this->picture ? asset($this->picture) : null;
    }

    public function admins()
    {
        return $this->belongsTo('App\Admin', 'uid');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_units()
    {
        return $this->belongsTo('App\Tbl_units', 'unit');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company');
    }

    public function tbl_invoice_products()
    {
        return $this->hasMany('App\Tbl_invoice_products', 'pro_id');
    }

    public function tbl_productcategory()
    {
        return $this->belongsTo('App\Tbl_productcategory', 'procat_id');
    }

    public function tbl_product_subcategory()
    {
        return $this->belongsTo('App\Tbl_product_subcategory', 'prosubcat_id');
    }

    public function tbl_deals()
    {
        return $this->hasMany('App\Tbl_products', 'pro_id');
    }

    public function tbl_group_products()
    {
        return $this->hasMany('App\Tbl_group_products', 'pro_id');
    }

    public function tbl_product_forms()
    {
        return $this->hasMany('App\Tbl_product_forms', 'pro_id');
    }

    public function tbl_leads()
    {
        return $this->hasMany('App\Tbl_leads', 'pro_id');
    }

    public function tbl_rds()
    {
        return $this->hasMany('App\Tbl_rds', 'pro_id');
    }

    public function tbl_user_cart()
    {
        return $this->hasMany('App\Tbl_user_cart', 'pro_id');
    }

    public function tbl_cart_orders()
    {
        return $this->hasMany('App\Tbl_cart_orders', 'pro_id');
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'product_id');
    }
}
