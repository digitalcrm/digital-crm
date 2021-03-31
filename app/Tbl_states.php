<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Tbl_states extends Model {

    //Table Name
    protected $table = 'tbl_states';
    //Primary key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'country_id'];

    public function tbl_leads() {
        return $this->hasMany('App\Tbl_leads', 'id');
    }

    public function tbl_accounts() {
        return $this->hasMany('App\Tbl_Accounts', 'id');
    }

    public function user() {
        return $this->hasMany('App\Tbl_states', 'id');
    }

    public function client() {
        return $this->hasMany('App\Tbl_states', 'id');
    }

    public function tbl_cart_orders() {
        return $this->hasMany('App\Tbl_cart_orders', 'id');
    }

    public function companies(){
        return $this->hasMany(Company::class,'state_id');
    }

}
