<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_project_members extends Model
{

    //Table Name
    protected $table = 'tbl_project_members';
    //Primary key
    public $primaryKey = 'pm_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pm_id', 'project_id', 'uid'
    ];

    public function tbl_projects()
    {
        return $this->belongsTo('App\Tbl_projects', 'project_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }
}
