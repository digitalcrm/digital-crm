<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasktype extends Model
{
    use SoftDeletes;


    protected $fillable = [

    	'name',
    ];

    public function todos() {

    	return $this->hasMany('App\Todo');
    }
}
