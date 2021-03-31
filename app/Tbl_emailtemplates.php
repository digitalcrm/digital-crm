<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_emailtemplates extends Model {

    //Table Name
    protected $table = 'tbl_email_templates';
    //Primary key
    public $primaryKey = 'temp_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['temp_id', 'ecat_id', 'subject', 'message'];    //'name'

    public function tbl_emailcategory() {
        return $this->belongsTo('App\Tbl_emailcategory', 'ecat_id');
    }

}
