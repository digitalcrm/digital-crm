<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_type extends Model
{
    //Table Name
    protected $table = 'ticket_types';

    //Primary key
    public $primaryKey = 'id';

    protected $fillable = ['name'];

    public function tickets() {

        return $this->hasMany('App\Ticket', 'type_id');
    }
}
