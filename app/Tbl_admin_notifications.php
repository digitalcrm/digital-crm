<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_admin_notifications extends Model {

    //Table Name
    protected $table = 'tbl_admin_notifications';
    //Primary key
    public $primaryKey = 'ad_not_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'ad_not_id',
        'aid',
        'message',
        'uid',
        'status',
        'type',
        'id'
    ];

    public function admin() {
        return $this->belongsTo('App\Admin', 'aid');
    }

}
