<?php

namespace App;

use App\Traits\DefaultProfile;
use Illuminate\Database\Eloquent\Model;

class Tbl_Appdetails extends Model
{
    use DefaultProfile;

    //Table Name
    protected $table = 'tbl_appdetails';

    //Primary key
    public $primaryKey = 'app_id';

    //Timestamps
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['app_id', 'app_name', 'app_picture'];

    protected $appends = ['profile_img'];

    public function getProfileImgAttribute()
    {
        return $this->profileLogo();
    }

    public function profileLogo()
    {
        return $this->app_picture ? asset($this->app_picture) : $this->defaultProfilePhotoUrl($this->app_name);
    }
    
}
