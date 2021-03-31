<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_rdpriority extends Model
{
    //Table Name
    protected $table = 'tbl_rd_priority';
    //Primary key
    public $primaryKey = 'rdpr_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'priority'
    ];

    public function tbl_rds()
    {
        return $this->hasMany('App\Tbl_rds', 'rdpr_id');
    }
}
