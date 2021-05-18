<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServSubcategory extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'serv_subcategories';

    public $primaryKey = 'id';

    protected $fillable = [
        'name', 
        'slug',
        'servcategory_id',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function serviceCategory() 
    {
        return $this->belongsTo( Servcategory::class, 'servcategory_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class,'service_serv_subcategory','service_id','serv_subcategory_id');
    }
}
