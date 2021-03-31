<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_project_status extends Model
{

    //Table Name
    protected $table = 'tbl_project_status';
    //Primary key
    public $primaryKey = 'ps_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ps_id', 'status'
    ];

    public function tbl_projects()
    {
        return $this->hasMany('App\Tbl_projects', 'ps_id');
    }
}
