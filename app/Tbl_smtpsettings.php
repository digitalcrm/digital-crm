<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_smtpsettings extends Model {

    //Table Name
    protected $table = 'tbl_smtp_settings';
    //Primary key
    public $primaryKey = 'ss_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'username', 'password', 'outgoingserver', 'outgoingport', 'incomingserver', 'incomingport'
    ];

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

//    public function tbl_mails() {
//        return $this->hasMany('App\Tbl_mails', 'ss_id');
//    }

    public function tbl_mails() {
        return $this->hasMany('App\Tbl_mails', 'ss_id');
    }
    
    public function tbl_mailers()
    {
        return $this->hasMany('App\Tbl_mailers', 'ss_id');
    }

}
