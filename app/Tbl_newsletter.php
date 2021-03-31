<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_newsletter extends Model {

    protected $table = "tbl_newsletter";
    public $primaryKey = "nl_id";
    public $timestamps = true;
    protected $fillable = ['nl_id', 'uid', 'title', 'subject', 'message', 'attachment', 'type', 'sent', 'failed', 'received',];

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

}
