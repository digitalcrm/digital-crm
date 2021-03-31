<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_emailcategory extends Model {

    //Table Name
    protected $table = 'tbl_emailcategory';
    //Primary key
    public $primaryKey = 'ecat_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ecat_id', 'category'];

    public function tbl_emails() {
        return $this->hasMany('App\Tbl_emails', 'ecat_id');
    }

    public function tbl_email_templates() {
        return $this->hasMany('App\Tbl_emailtemplates', 'ecat_id');
    }

}
