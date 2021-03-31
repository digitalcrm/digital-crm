<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class BookingCustomer extends Model
{
    use HasFactory;

    protected $table = 'booking_customers';

    protected $primaryKey = 'id';

    protected $fillable = ['customer_name', 'email', 'mobile_number', 'description','booking_event_id', 'user_id', 'start_from', 'to_end'];

    protected $dates = [
        'start_from',
        'to_end',
        'created_at',
        'updated_at',
    ];

    public function bookingEvent()
    {
        return $this->belongsTo( BookingEvent::class )->withDefault();
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

}
