<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_admin_mails extends Model {

    //Table Name
    protected $table = 'tbl_admin_mails';
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
        'aid',
        'uid',
        'id',
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

    public function admin() {
        return $this->belongsTo('App\Admin', 'aid');
    }

}
