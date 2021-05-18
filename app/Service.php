<?php

namespace App;

use App\Traits\DefaultProfile;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasSlug, DefaultProfile;

    protected $table = 'services';

    public $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'price',
        'user_id',
        'company_id',
        'servcategory_id',
        'brand',
        'tags',
        'views',
        'isActive',
        'isFeatured',
        'location',
        'unit_id', // nullable default
        'minimum_order', // default set 1 number
        'delivery_time', // string local 9 am to 7 pm
        'specification', // it would be a json coulum
    ];

    protected $appends = ['service_img'];

    public function getServiceImgAttribute()
    {
        return $this->image ? asset('public/storage/'.$this->image) : $this->defaultProfilePhotoUrl($this->title);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->saveSlugsTo('slug');
    }
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function serviceCategory()
    {
        return $this->belongsTo(Servcategory::class, 'servcategory_id');
    }

    public function serviceSubcategories()
    {
        return $this->belongsToMany(ServSubcategory::class, 'service_serv_subcategory', 'service_id', 'serv_subcategory_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function scopeIsActive($query)
    {
        return $query->where('isActive', true);
    }

    public function scopeIsFeatured($query, $value)
    {
        $query->when(($value === 'true'), function($query) {
            return $query->where('isFeatured',1);
        });
    }

    public function scopeSearch($query, $value)
    {
        return $query
            ->whereHas('user', function($q) use($value) {
                $q->where('name', 'like', '%' . $value . '%');
            })
            ->orWhere('title', 'like', '%' . $value . '%')
            ->orWhere('description', 'like', '%' . $value . '%');
    }

    /**
     * get the service having subcategories
     *
     */
    public function getSubcategoryIds()
    {
        $subcats = $this->serviceSubcategories()->get()->map(function ($subcat) {
            return $subcat->id;
        });

        if ($subcats == '') return '';

        return $subcats;
    }
}
