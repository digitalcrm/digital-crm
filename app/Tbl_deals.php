<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_deals extends Model
{

    //Table Name
    protected $table = 'tbl_deals';
    //Primary key
    public $primaryKey = 'deal_id';
    //Timestamps
    public $timestamps = true;
    protected $fillable = [
        'deal_id', 'uid', 'ld_id', 'acc_id', 'sfun_id', 'ldsrc_id', 'name', 'title', 'value', 'closing_date', 'notes', 'message', 'picture', 'deal_status', 'active', 'probability', 'lr_id', 'pro_id', 'dl_id'
    ];

    public function tbl_leadsource()
    {
        return $this->belongsTo('App\Tbl_leadsource', 'ldsrc_id');
    }

    public function tbl_salesfunnel()
    {
        return $this->belongsTo('App\Tbl_salesfunnel', 'sfun_id');
    }

    public function tbl_leads()
    {
        return $this->belongsTo('App\Tbl_leads', 'ld_id');
    }

    public function tbl_accounts()
    {
        return $this->belongsTo('App\Tbl_Accounts', 'acc_id');
    }

    public function users()
    {
        return $this->belongsTo('App\user', 'uid');
    }

    public function tbl_lossreasons()
    {
        return $this->belongsTo('App\Tbl_lossreasons', 'lr_id');
    }

    public function tbl_products()
    {
        return $this->belongsTo('App\Tbl_products', 'pro_id');
    }

    public function tbl_deal_events()
    {
        return $this->hasMany('App\Tbl_deal_events', 'deal_id');
    }

    public function tbl_post_order_stage()
    {
        return $this->belongsTo('App\Tbl_post_order_stage', 'pos_id');
    }

    public function tbl_orders()
    {
        return $this->hasOne('App\Tbl_orders', 'deal_id');
    }

    public function tbl_deal_types()
    {
        return $this->belongsTo('App\Tbl_deal_types', 'dl_id');
    }

    public function tbl_paymentstatus()
    {
        return $this->belongsTo('App\Tbl_paymentstatus', 'paystatus_id');
    }
}
