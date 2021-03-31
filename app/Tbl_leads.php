<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_leads extends Model
{

    //Table Name
    protected $table = 'tbl_leads';
    //Primary key
    public $primaryKey = 'ld_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ld_id', 'uid', 'fl_id', 'first_name', 'last_name', 'email', 'picture', 'phone', 'mobile', 'website', 'notes', 'message', 'acc_id', 'ldsrc_id', 'ldstatus_id', 'intype_id', 'cnt_id', 'street', 'city', 'state', 'country', 'zip', 'latitude', 'longitude', 'active', 'company', 'assigned', 'sal_id', 'fblead_id', 'uploaded_from', 'uploaded_id', 'designation', 'pro_id', 'leadtype'
    ];

    public function tbl_leadsource()
    {
        return $this->belongsTo('App\Tbl_leadsource', 'ldsrc_id');
    }

    public function tbl_leadstatus()
    {
        return $this->belongsTo('App\Tbl_leadstatus', 'ldstatus_id');
    }

    public function tbl_industrytypes()
    {
        return $this->belongsTo('App\Tbl_industrytypes', 'intype_id');
    }

    public function tbl_countries()
    {
        return $this->belongsTo('App\Tbl_countries', 'country');
    }

    public function tbl_states()
    {
        return $this->belongsTo('App\Tbl_states', 'state');
    }

    public function tbl_accounts()
    {
        return $this->belongsTo('App\Tbl_Accounts', 'acc_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_formleads()
    {
        return $this->belongsTo('App\Tbl_formleads', 'fl_id');
    }

    public function tbl_deals()
    {
        return $this->hasMany('App\Tbl_deals', 'ld_id');
    }

    //    public function tbl_contacts() {
    //        return $this->hasOne('App\Tbl_leads', 'ld_id');
    //    }

    public function tbl_notifications()
    {
        return $this->hasMany('App\Tbl_notifications', 'ld_id');
    }

    public function tbl_invoice()
    {
        return $this->hasMany('App\Tbl_invoice', 'ld_id');
    }

    public function tbl_cart_orders()
    {
        return $this->hasMany('App\Tbl_cart_orders', 'ld_id');
    }

    public function tbl_salutations()
    {
        return $this->belongsTo('App\Tbl_salutations', 'sal_id');
    }

    public function tbl_products()
    {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

    public function todos()
    {
        return $this->morphMany('App\Todo', 'todoable');
    }

    //
    //    public function tbl_contacts() {
    //        return $this->hasMany('App\Tbl_contacts', 'ld_id');
    //    }
}
