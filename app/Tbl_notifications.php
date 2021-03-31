<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_notifications extends Model {

    //Table Name
    protected $table = 'tbl_notifications';
    //Primary key
    public $primaryKey = 'not_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message', 'uid', 'status', 'type', 'id'];

    public function users() {
        return $this->hasMany('App\User', 'uid');
    }

    public function tbl_leads() {
        return $this->hasMany('App\Tbl_leads', 'id');
    }

    public function tbl_formleads() {
        return $this->hasMany('App\Tbl_formleads', 'id');
    }

}
