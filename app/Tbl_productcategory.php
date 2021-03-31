<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Tbl_productcategory extends Model
{

    //Table Name
    protected $table = 'tbl_product_category';
    //Primary key
    public $primaryKey = 'procat_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['procat_id', 'category', 'slug', 'picture'];

    protected $appends = ['logo'];

    public function getLogoAttribute()
    {
        return $this->companyLogo();
    }

    public function companyLogo()
    {
        return $this->picture
            ? url($this->picture)
            : $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->category) . '&color=7F9CF5&background=EBF4AA';
    }

    public function tbl_products()
    {
        return $this->hasMany('App\Tbl_products', 'procat_id');
    }

    public function tbl_product_subcategory()
    {
        return $this->hasMany('App\Tbl_product_subcategory', 'procat_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'category_id');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class, 'product_category_id');
    }
}
