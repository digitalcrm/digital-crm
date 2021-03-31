<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_departments extends Model {

    //Table Name
    protected $table = 'tbl_departments';
    //Primary key
    public $primaryKey = 'dep_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dep_id', 'department'];

    public function tbl_emails() {
        return $this->hasMany('App\Tbl_emails', 'dep_id');
    }

    public function tbl_email_templates() {
        return $this->hasMany('App\Tbl_emailtemplates', 'dep_id');
    }

}
