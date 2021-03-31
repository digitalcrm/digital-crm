<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_features extends Model
{

    //Table Name
    protected $table = 'tbl_features';
    //Primary key
    public $primaryKey = 'ft_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = ['ft_id', 'uid', 'webtolead', 'accounts', 'contacts', 'leads', 'deals', 'customers', 'sales', 'orders', 'territory', 'products', 'documents', 'invoices', 'projects', 'rds', 'webmails', 'mails', 'tasks', 'settings', 'reports', 'companies', 'appointments', 'productleads', 'campaigns', 'ticketing'];

    public function users()
    {
        return $this->belongsTo('App\User', 'uid');
    }
}
