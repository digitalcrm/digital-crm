<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket_status extends Model
{
    //Table Name
    protected $table = 'ticket_statuses';

    //Primary key
    public $primaryKey = 'id';

    protected $fillable = ['name'];

    public function tickets() {

        return $this->hasMany('App\Ticket', 'status_id');
    }
}
