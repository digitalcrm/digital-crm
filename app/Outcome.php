<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outcome extends Model
{
    use SoftDeletes;

    //Table Name
    protected $table = 'outcomes';
    //Primary key
    public $primaryKey = 'id';

    protected $fillable = [

    	'name'

    ];


    public function todos() {

    	return $this->hasMany('App\Todo');
    }
}
