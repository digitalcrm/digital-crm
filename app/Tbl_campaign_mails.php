<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_campaign_mails extends Model {

    //Table Name
    protected $table = 'tbl_campaign_mails';
    //Primary key
    public $primaryKey = 'cmail_id';
    //Timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cmail_id',
        'uid',
        'camp_id',
        'temp_id',
        'subject',
        'message',
        'attachments',
        'ids',
        'emails',
        'names',
        'type',
        'status',
        'mail_type',
        'active',
    ];

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_campaigns() {
        return $this->belongsTo('App\Tbl_campaigns', 'camp_id');
    }

    public function tbl_mail_templates() {
        return $this->belongsTo('App\Tbl_mail_templates', 'temp_id');
    }

}
