<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_config extends Model
{
    //Table Name
    protected $table = 'tbl_config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['domain', 'created_date', 'secret_key'];
}
