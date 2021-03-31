<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    //Table Name
    protected $table = 'tickets';
    //Primary key
    public $primaryKey = 'id';

    protected $fillable = [
        'ticket_number',
        'name',
        'description',
        'contact_id',
        'product_id',
        'status_id',
        'type_id',
        'priority_id',
        'user_id',
        'ticket_image',
        'start_date',

    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    protected $dates = [
        'start_date',
        'deleted_at',
    ];

    protected $filepath = '/storage/';

    public function getRouteKeyName()
    {
        return 'ticket_number';
    }

    # Mutator defiened for start_date
    public function setStartDateAttribute($date)
    {
        $this->attributes['start_date'] = Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    # Get Ticket Image using Accessor method
    // public function getTicketImageAttribute($value)
    // {
    //     // return asset($value ? $this->filepath.$value: 'uploads/default/products.jpg');
    // }

    public function ticketfileimage() {
        return asset( !empty($this->ticket_image) ? 'public/storage/'.$this->ticket_image : 'uploads/default/products.jpg');
    }

    #Relationship established
    public function ticketType() {

        return $this->belongsTo( 'App\Ticket_type', 'type_id' );
    }

    public function ticketStatus() {

        return $this->belongsTo( 'App\Ticket_status', 'status_id' );
    }

    /**
     * #  withDefault will help to prevent an error if record is permanently deleted.
     * #  https://laraveldaily.com/did-you-know-five-additional-filters-in-belongsto-or-hasmany/
     *
     *
     */
    public function tbl_contacts() {
        return $this->belongsTo('App\Tbl_contacts', 'contact_id')->withDefault();
    }

    public function tbl_products() {
        return $this->belongsTo('App\Tbl_products', 'product_id');
    }

    public function users() {

        // return $this->belongsTo('App\User', 'user_id')->where('active',1);
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get all of the Ticket's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function scopeByAuthUser($query)
    {
        return $query->where('user_id','=', Auth::user()->id);
    }

    /**
     * scope Query for filter the status value
     */
    public function scopeWithStatus($query, $status_name)
    {
        return $query->whereHas('ticketStatus', function($query) use ($status_name) {
                $query->where('name', $status_name);
            });
    }
    /**
     * scope Query for filter the priority value
     */
    public function scopeWithPriority($query, $priority_name)
    {
        return $query->whereHas('priority', function($query) use ($priority_name) {
                $query->where('name', $priority_name);
        });
    }

    public function scopeWithUnassignedTickets($query) {

        return $query->where('user_id','=',Null);

    }
    public function scopeWithoutUnassignedTickets($query) {

        return $query->where('user_id','!=',Null);

    }
    /**
     * Relationship build
     */
    public function priority() {

        return $this->belongsTo( 'App\Priority','priority_id');
    }

}
