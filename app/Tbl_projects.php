<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_projects extends Model
{

    //Table Name
    protected $table = 'tbl_projects';
    //Primary key
    public $primaryKey = 'project_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'uid', 'name', 'code', 'description', 'type', 'forecast', 'manager', 'members', 'weights', 'total_forecast', 'deal_id', 'active', 'ps_id', 'creation_date', 'submission_date', 'leaves', 'different_project', 'company_activity', 'working', 'other', 'total_days', 'marketing_collateral', 'sample_pages', 'client_submit', 'feedback','actual_days'];

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_project_members()
    {
        return $this->hasMany('App\Tbl_project_members', 'project_id');
    }

    public function tbl_project_status()
    {
        return $this->belongsTo('App\Tbl_project_status', 'ps_id');
    }
    
    public function todos()
    {
        return $this->hasMany('App\Todo', 'project_id');
    }
}
