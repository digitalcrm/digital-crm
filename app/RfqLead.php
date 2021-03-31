<?php

namespace App;

use App\Rfq;
use App\Tbl_countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RfqLead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rfq_leads';

    protected $fillable = [
        'rfq_id',
        'contact_name',
        'email',
        'mobile_number',
        'address',
        'country_id',
        'city',
        'message',
    ];

    public function country()
    {
        return $this->belongsTo(Tbl_countries::class, 'country_id');
    }

    public function rfq()
    {
        return $this->belongsTo(Rfq::class, 'rfq_id');
    }
}
