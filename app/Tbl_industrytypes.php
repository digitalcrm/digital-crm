<?php

namespace App;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Tbl_industrytypes extends Model
{
     //Table Name
    protected $table = 'tbl_industrytypes';
    //Primary key
    public $primaryKey = 'intype_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['intype_id','type'];

    public function tbl_leads(){
        return $this->hasMany('App\Tbl_leads','intype_id');
    }

    public function tbl_accounts(){
        return $this->hasMany('App\Tbl_Accounts','acc_id');
    }

    public function tbl_rds(){
        return $this->hasMany('App\Tbl_rds','intype_id');
    }
}
