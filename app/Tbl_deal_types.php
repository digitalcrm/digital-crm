<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_deal_types extends Model
{

    //Table Name
    protected $table = 'tbl_deal_types';
    //Primary key
    public $primaryKey = 'dl_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dl_id', 'type'];

    public function tbl_deals()
    {
        return $this->hasMany('App\Tbl_deals', 'dl_id');
    }
}
