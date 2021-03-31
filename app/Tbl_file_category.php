<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_file_category extends Model {

    //Table Name
    protected $table = 'tbl_file_category';
    //Primary key
    public $primaryKey = 'fc_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = ['fc_id', 'category'];

    public function tbl_file_manager() {
        return $this->hasMany('App\Tbl_file_manager', 'fc_id');
    }

}
