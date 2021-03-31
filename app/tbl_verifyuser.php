<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_verifyuser extends Model {

    //Table Name
    protected $table = 'tbl_verifyuser';
    //Primary key
    public $primaryKey = 'vu_id';
    //Timestamps
    public $timestamps = true;
    //Fillable columns
    protected $fillable = ['vu_id', 'uid', 'token'];

    public function users() {
        return $this->belongsTo('App\User', 'uid');
    }

}
