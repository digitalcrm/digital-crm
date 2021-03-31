<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_fb_leads extends Model {

    //Table Name
    protected $table = 'tbl_fb_leads';
    //Primary key
    public $primaryKey = 'fblead_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'fblead_id', 'id', 'created_time', 'ad_id', 'ad_name', 'adset_id', 'adset_name', 'campaign_id', 'campaign_name', 'form_id', 'form_name', 'is_organic', 'platform', 'full_name', 'city', 'phone_number', 'uid', 'uploaded_by', 'assigned', 'file_id'
    ];

    public function tbl_leads_csv_files() {
        return $this->belongsTo('App\Tbl_leads_csv_files', 'file_id');
    }

    public function tbl_product_forms() {
        return $this->hasMany('App\Tbl_product_forms', 'form_id');
    }

}
