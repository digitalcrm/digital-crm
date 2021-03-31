<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_leadstatus extends Model
{

    //Table Name
    protected $table = 'tbl_leadstatus';
    //Primary key
    public $primaryKey = 'ldstatus_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ldstatus_id', 'status', 'deal'];    //event

    public function tbl_leads()
    {
        return $this->hasMany('App\Tbl_leads', 'ldstatus_id');
    }
}
