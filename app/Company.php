<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'companies';
    //Primary key
    public $primaryKey = 'id';

    protected $fillable = [
        'c_name',
        'slug',
        'views',
        'personal_name',
        'c_mobileNum',
        'c_whatsappNum',
        'c_email',
        'c_logo',
        'document',
        'c_cover',
        'position',
        'c_bio',
        'user_id',
        'actype_id',
        'category_id',
        'country_id',
        'state_id',
        'address',
        'city',
        'zipcode',
        'c_gstNumber',
        'c_webUrl',
        'google_map_url',
        'yt_video_link',
        'fb_link',
        'tw_link',
        'yt_link',
        'linkedin_link',
        'isActive',
        'showLive',
        'termsAccept',
        'employees',
        'admin_id'
    ];

    protected $appends = [
        'logo',
        'cover_img',
    ];

    public function getLogoAttribute()
    {
        return $this->companyLogo();
    }

    public function downloadCatalog()
    {
        try {
            return response()->download(asset('public/storage/'.$this->document));
        } catch (\Throwable $th) {
            return abort(404);
        }
    }

    public function getCoverImgAttribute()
    {
        return $this->companyCover();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('c_name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function companyLogo()
    {
        return $this->c_logo
            ? asset('public/storage/' . $this->c_logo)
            : $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->c_name) . '&color=7F9CF5&background=EBF4AA';
    }

    public function companyCover()
    {
        return $this->c_cover ? asset('public/storage/' . $this->c_cover) : null;
    }

    // public function admins()
    // {
    //     return $this->belongsTo('App\Admin', 'admin_id');
    // }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function tbl_products()
    {
        return $this->hasMany('App\Tbl_products', 'company');
    }

    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country_id');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state_id');
    }

    public function scopeIsActive($query)
    {
        return $query->where('isActive', true);
    }

    public function tbl_accounttypes()
    {
        return $this->belongsTo('App\Tbl_accounttypes', 'actype_id');
    }

    public function tbl_product_category()
    {
        return $this->belongsTo('App\Tbl_productcategory', 'category_id');
    }

    public function productLead()
    {
        // $data = Tbl_leads::has('tbl_products')->where('uid',auth()->id())->where('active', 1)->where('leadtype',2)->count();
        $data = $this->leads()->where('uid',auth()->id())->where('active', 1)->where('leadtype',2)->count();
        return $data;
    }

    public function leads()
    {
        return $this->hasMany(Tbl_leads::class, 'company');
    }

    public function scopeSearch($query, $val)
    {
        // $searchInput = '%'.$val.'%';
        $searchInput = $val.'%';
        return $query->where('city','like',$searchInput);
    }
}
