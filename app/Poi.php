<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poi extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'pois';

    protected $fillable = [
        'product_name',
        'slug',
        'user_id',
        'product_category_id',
        'sub_category_id',
        'isActive',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_name')
            ->saveSlugsTo('slug');
    }

    public function tbl_category()
    {
        return $this->belongsTo( Tbl_productcategory::class, 'product_category_id');
    }

    public function tbl_sub_category()
    {
        return $this->belongsTo( Tbl_product_subcategory::class, 'sub_category_id');
    }
}
