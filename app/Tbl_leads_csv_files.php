<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_leads_csv_files extends Model {

    //Table Name
    protected $table = 'tbl_leads_csv_files';
    //Primary key
    public $primaryKey = 'file_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'file_id', 'name', 'original_name', 'type', 'content_type', 'document', 'size', 'views', 'active', 'status', 'uid', 'uploaded_by', 'import_time'
    ];

    public function tbl_fb_leads() {
        return $this->hasMany('App\Tbl_fb_leads', 'file_id');
    }

}
