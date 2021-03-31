<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_forms extends Model {

    //Table Name
    protected $table = 'tbl_forms';
    //Primary key
    public $primaryKey = 'form_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'title',
        'post_url',
        'redirect_url',
        'from_mail',
        'views',
        'submissions',
        'subject',
        'message',
        'status',
        'logo',
        'site_key',
        'secret_key',
        'active',
        'form_key',
    ];

    public function tbl_formleads() {
        return $this->hasMany('App\Tbl_formleads', 'form_id');
    }

    public function tbl_formviews() {
        return $this->hasMany('App\Tbl_formviews', 'form_id');
    }

    public function users() {
        return $this->hasMany('App\User', 'uid');
    }

}
