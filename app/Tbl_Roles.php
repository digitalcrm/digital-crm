<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_Roles extends Model
{
    //Table Name
    protected $table = 'tbl_roles';
    //Primary key
    public $primaryKey = 'role_id';
    //Timestamps
    public $timestamps = true;

    protected $fillable = ['role_id', 'name'];

    public function tbl_admin_permissions()
    {
        return $this->hasMany('App\Tbl_Admin_Permissions', 'permission_id');
    }
}
