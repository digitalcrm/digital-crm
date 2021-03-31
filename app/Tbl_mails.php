<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_mails extends Model {

    //
    //Table Name
    protected $table = 'tbl_mails';
    //Primary key
    public $primaryKey = 'mail_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail_id',
        'uid',
        'ss_id',
        'id',
        'subject',
        'from_address',
        'from_name',
        'message',
        'message_type',
        'date',
        'attachments',
        'ids',
        'emails',
        'names',
        'type',
        'status',
        'active',
        'mail_type'
    ];

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_smtp_settings() {
        return $this->belongsTo('App\Tbl_smtpsettings', 'ss_id');
    }

}
