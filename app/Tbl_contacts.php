<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_contacts extends Model
{

    //Table Name
    protected $table = 'tbl_contacts';
    //Primary key
    public $primaryKey = 'cnt_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'cnt_id', 'uid', 'first_name', 'last_name', 'email', 'picture', 'mobile', 'phone',
        'notes', 'company', 'website', 'latitude', 'longitude', 'country', 'state', 'city', 'street', 'zip',
        'hn_id', 'company', 'google_id', 'facebook_id', 'twitter_id', 'linkedin_id', 'ld_id', 'acc_id', 'ldsrc_id', 'form_id', 'latitude', 'longitude', 'active', 'sal_id', 'designation'
    ];

    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state');
    }

    public function tbl_leadsource()
    {
        return $this->belongsTo('App\Tbl_leadsource', 'ldsrc_id');
    }

    public function users()
    {
        return $this->belongsTo('App\user', 'uid');
    }

    //
    //    public function tbl_leads() {
    //        return $this->belongsTo('App\Tbl_leads', 'ld_id');
    //    }

    public function tbl_accounts()
    {
        return $this->belongsTo('App\Tbl_Accounts', 'acc_id');
    }

    //    public function tbl_leads() {
    //        return $this->hasOne('App\Tbl_leads', 'ld_id');
    //    }


    public function tbl_salutations()
    {
        return $this->belongsTo('App\Tbl_salutations', 'sal_id');
    }

    public function tickets() {

        return $this->hasMany('App\Ticket', 'contact_id');
    }

    public function fullname() {

        return ucfirst($this->first_name) .' '. ucfirst($this->last_name);
    }
}
