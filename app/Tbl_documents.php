<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_documents extends Model {

    //Table Name
    protected $table = 'tbl_documents';
    //Primary key
    public $primaryKey = 'doc_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = ['doc_id', 'uid', 'name', 'type', 'content_type', 'document', 'size', 'views', 'active',];

    public function users() {
        return $this->belongsTo('App\user', 'uid');
    }

}
