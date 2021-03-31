<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_emails extends Model {

    //Table Name
    protected $table = 'tbl_emails';
    //Primary key
    public $primaryKey = 'mail_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mail_id', 'mail', 'ecat_id'];

    public function tbl_emailcategory() {
        return $this->belongsTo('App\Tbl_emailcategory', 'ecat_id');
    }

}
