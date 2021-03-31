<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_test_leads extends Model {

    //Table Name
    protected $table = 'tbl_test_leads';
    //Primary key
    public $primaryKey = 'tlid';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'data'
    ];


}
