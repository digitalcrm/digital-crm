<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_mail_templates extends Model {

    //Table Name
    protected $table = 'tbl_mail_templates';
    //Primary key
    public $primaryKey = 'temp_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'temp_id',
        'uid',
        'name',
        'subject',
        'message',
        'active',
    ];

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_campaign_mails() {
        return $this->hasMany('App\Tbl_campaign_mails', 'temp_id');
    }

}
