<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_rds extends Model
{

    //Table Name
    protected $table = 'tbl_rds';
    //Primary key
    public $primaryKey = 'rd_id';
    //Timestamps
    public $timestamps = true;

    protected $fillable = ['rd_id', 'uid', 'title', 'rdtype_id', 'intype_id', 'status', 'link', 'creation_date', 'submission_date', 'active', 'user_type', 'uploaded_date', 'pro_id', 'rdpr_id', 'rdtr_id'];

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    public function tbl_industrytypes()
    {
        return $this->belongsTo('App\Tbl_industrytypes', 'intype_id');
    }

    public function tbl_rdtypes()
    {
        return $this->belongsTo('App\Tbl_rdtypes', 'rdtype_id');
    }
    
    public function tbl_products()
    {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }
    
    public function tbl_rd_priority()
    {
        return $this->belongsTo('App\Tbl_rdpriority', 'rdpr_id');
    }
    
    public function tbl_rd_trends()
    {
        return $this->belongsTo('App\Tbl_rdtrend', 'rdtr_id');
    }
}
