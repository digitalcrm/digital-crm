<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rfq extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'rfqs';

    protected $fillable = [
        'product_name',
        'slug',
        'views',
        'rfq_profile',
        'user_id',
        'product_category_id',
        'sub_category_id',
        'product_quantity',
        'unit_id',
        'purchase_price',
        'city',
        'details',
        'isChecked',
        'isActive',
        'company_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // public function city()
    // {
    //     return $this->belongsTo( City::class, 'city_id')->withDefault();
    // }

    public function tbl_category()
    {
        return $this->belongsTo( Tbl_productcategory::class, 'product_category_id');
    }

    public function tbl_sub_category()
    {
        return $this->belongsTo( Tbl_product_subcategory::class, 'sub_category_id');
    }

    // public function currency()
    // {
    //     return $this->belongsTo( currency::class, 'currency_id');
    // }

    public function unit()
    {
        return $this->belongsTo( Tbl_units::class, 'unit_id');
    }

    public function user()
    {
        return $this->belongsTo( User::class, 'user_id')->withDefault();
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function GetSingleImage()
    {
        return $this->morphMany(Image::class, 'imageable')->orderByDesc('id','desc')->limit(1);
    }

    public function scopeCategoryFilter($query, $request)
    {
        return $query->when($request, function($q){
            $q->whereHas('tbl_category', function($q1){
                $q1->where('category', request('category'));
            });
        });
    }

    public function scopeSubcategoryFilter($query, $request)
    {
        return $query->when($request, function($q){
            $q->whereHas('tbl_sub_category', function($q1){
                $q1->where('category', request('subcategory'));
            });
        });
    }

    public function scopeIsActive($query)
    {
        return $query->where('isActive', true);
    }

    public function rfqLeads()
    {
        return $this->hasMany(RfqLead::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeRfqInquiry($query, $requestValue)
    {
        return $query->when($requestValue, function ($query, $requestValue) {
            return $query->where('slug', $requestValue);
        });
    }
}
