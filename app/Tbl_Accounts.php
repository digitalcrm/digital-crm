<?php

namespace App;

use App\Traits\DefaultProfile;
use Illuminate\Database\Eloquent\Model;

class Tbl_Accounts extends Model
{
    use DefaultProfile;

    //Table Name
    protected $table = 'tbl_accounts';
    //Primary key
    public $primaryKey = 'acc_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acc_id',
        'uid',
        'name',
        'email',
        'picture',
        'mobile',
        'phone',
        'company',
        'employees',
        'website',
        'description',
        'actype_id',
        'intype_id',
        'street',
        'city',
        'state',
        'country',
        'zip',
        'latitude',
        'longitude',
        'active',
    ];

    public function profileLogo()
    {
        return $this->picture ? asset($this->picture) : $this->defaultProfilePhotoUrl($this->name);
    }
    
    public function tbl_leads()
    {
        return $this->hasMany('App\Tbl_leads', 'acc_id');
    }

    public function tbl_contacts()
    {
        return $this->hasMany('App\Tbl_contacts', 'acc_id');
    }

    public function users()
    {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_industrytypes()
    {
        return $this->belongsTo('App\Tbl_industrytypes', 'intype_id');
    }

    public function tbl_accounttypes()
    {
        return $this->belongsTo('App\Tbl_accounttypes', 'actype_id');
    }

    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state');
    }

    // public function tbl_company()
    // {
    //     return $this->hasMany('App\Tbl_company', 'acc_id');
    // }

    public function haveCompany()
    {
        return $this->belongsTo(Company::class, 'company');
    }
}
