<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priority extends Model
{
    use SoftDeletes;

    protected $table = 'priorities';

    public $primaryKey = 'id';

    protected $fillable = ['name', 'color'];

    public function todos() {

    	return $this->hasMany('App\Todo');
    }

    public function tickets() {

    	return $this->hasMany('App\Ticket','priority_id');
    }
}
