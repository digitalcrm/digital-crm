<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_Admin_Permissions extends Model
{
    //
    //Table Name
    protected $table = 'tbl_admin_permissions';

    public function tbl_permissions()
    {
        return $this->belongsTo('App\Tbl_permissions', 'permission_id');
    }

    public function admins()
    {
        return $this->belongsTo('App\Admin', 'admin_id');
    }
}
