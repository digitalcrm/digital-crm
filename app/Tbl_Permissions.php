<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_Permissions extends Model
{
    //Table Name
    protected $table = 'tbl_permissions';
    //Primary key
    public $primaryKey = 'permission_id';
    //Timestamps
    public $timestamps = true;

    protected $fillable = ['permission_id', 'name'];

    public function tbl_admin_permissions()
    {
        return $this->hasMany('App\Tbl_Admin_Permissions', 'permission_id');
    }
}
