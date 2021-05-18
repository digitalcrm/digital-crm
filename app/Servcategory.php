<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servcategory extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'servcategories';

    public $primaryKey = 'id';

    protected $fillable = [
        'name', 
        'slug',
        'image',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function servSubCategory()
    {
        return $this->hasMany( ServSubcategory::class, 'servcategory_id');
    }

    public function services()
    {
        return $this->hasMany( Service::class, 'servcategory_id');
    }
}
