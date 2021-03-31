<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_company extends Model
{
    //

    //Table Name
    protected $table = 'tbl_company';
    //Primary key
    public $primaryKey = 'comp_id';
    //Timestamps
    public $timestamps = true;

    protected $fillable = [
        'acc_id',
        'uid',
        'name',
        'email',
        'picture',
        'mobile',
        'phone',
        'website',
        'description',
        'street',
        'city',
        'state',
        'country',
        'zip',
        'active',
    ];

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_accounts()
    {
        return $this->belongsTo('App\Tbl_accounts', 'acc_id');
    }
}
