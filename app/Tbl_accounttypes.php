<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Tbl_accounttypes extends Model {

    //Table Name
    protected $table = 'tbl_accounttypes';
    //Primary key
    public $primaryKey = 'actype_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['actype_id', 'account_type'];

    public function tbl_accounts() {
        return $this->hasMany('App\Tbl_Accounts', 'actype_id');
    }

    public function companies(){
        return $this->hasMany(Company::class,'actype_id');
    }

}
