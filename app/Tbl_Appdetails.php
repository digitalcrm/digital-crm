<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_Appdetails extends Model
{
    //Table Name
    protected $table = 'tbl_appdetails';

    //Primary key
    public $primaryKey = 'app_id';
    
    //Timestamps
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['app_id', 'app_name', 'app_picture'];
}
