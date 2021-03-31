<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_campaigns extends Model {

    //Table Name
    protected $table = 'tbl_campaigns';
    //Primary key
    public $primaryKey = 'camp_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'camp_id',
        'uid',
        'name',
        'start_date',
        'end_date',
        'status_id',
        'type_id',
        'budget',
        'actual_cost',
        'expected_cost',
        'expected_revenue',
        'objective',
        'description',
        'active',
    ];

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_camp_status() {
        return $this->belongsTo('App\Tbl_camp_status', 'status_id');
    }

    public function tbl_camp_type() {
        return $this->belongsTo('App\Tbl_camp_types', 'type_id');
    }

    public function tbl_campaign_mails() {
        return $this->hasMany('App\Tbl_campaign_mails', 'camp_id');
    }

}
