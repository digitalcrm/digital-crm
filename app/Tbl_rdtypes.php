<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_rdtypes extends Model
{

    //Table Name
    protected $table = 'tbl_rdtypes';
    //Primary key
    public $primaryKey = 'rdtype_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];

    public function tbl_rds()
    {
        return $this->hasMany('App\Tbl_rds', 'rdtype_id');
    }
}
