<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_images extends Model {

    //Table Name
    protected $table = 'tbl_images';
    //Primary key
    public $primaryKey = 'img_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'img_id',
        'type',
        'image',
    ];

}
