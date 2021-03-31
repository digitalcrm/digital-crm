<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_leadsource extends Model
{
    //Table Name
    protected $table = 'tbl_leadsource';
    //Primary key
    public $primaryKey = 'ldsrc_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ldsrc_id','leadsource'];


    public function tbl_leads(){
        return $this->hasMany('App\Tbl_leads','ldsrc_id');
    }

    public function tbl_contacts(){
        return $this->hasMany('App\Tbl_contacts','ldsrc_id');
    }
}
