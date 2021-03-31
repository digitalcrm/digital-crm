<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_verifyclient extends Model
{
    use HasFactory;

    //Table Name
    protected $table = 'tbl_verifyclient';
    //Primary key
    public $primaryKey = 'vc_id';
    //Timestamps
    public $timestamps = true;
    //Fillable columns
    protected $fillable = ['vc_id', 'cl_id', 'token'];

    public function client()
    {
        return $this->belongsTo('App\Client', 'cl_id');
    }
}
