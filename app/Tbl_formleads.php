<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_formleads extends Model {

    //Table Name
    protected $table = 'tbl_formleads';
    //Primary key
    public $primaryKey = 'fl_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'website', 'notes', 'form_id', 'uid', 'lead', 'active'
    ];

    public function tbl_notifications() {
        return $this->hasMany('App\Tbl_notifications', 'fl_id');
    }

    public function tbl_leads() {
        return $this->hasOne('App\Tbl_leads', 'fl_id');
    }

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_forms() {
        return $this->belongsTo('App\Tbl_forms', 'form_id');
    }

}
