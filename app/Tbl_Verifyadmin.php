<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_Verifyadmin extends Model
{
    //Table Name
    protected $table = 'tbl_verifyadmin';
    //Primary key
    public $primaryKey = 'va_id';
    //Timestamps
    public $timestamps = true;
    //Fillable columns
    protected $fillable = ['va_id', 'admin_id', 'token'];

    public function admin()
    {
        return $this->belongsTo('App\Admin', 'admin_id');
    }
}
