<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_formviews extends Model {

    //Table Name
    protected $table = 'tbl_formviews';
    //Primary key
    public $primaryKey = 'fv_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id',
    ];

    public function tbl_forms() {
        return $this->belongsTo('App\Tbl_forms', 'form_id');
    }

}
