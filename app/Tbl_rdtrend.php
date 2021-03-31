<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_rdtrend extends Model
{
    //Table Name
    protected $table = 'tbl_rd_trends';
    //Primary key
    public $primaryKey = 'rdtr_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trend'
    ];

    public function tbl_rds()
    {
        return $this->hasMany('App\Tbl_rds', 'rdtr_id');
    }
}
