<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_file_manager extends Model {

    //Table Name
    protected $table = 'tbl_file_manager';
    //Primary key
    public $primaryKey = 'file_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'fc_id',
        'name',
        'type',
        'content_type',
        'file',
        'size',
        'views',
        'active',
    ];

    public function tbl_file_category() {
        return $this->belongsTo('App\Tbl_file_category', 'fc_id');
    }

}
