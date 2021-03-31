<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;

    //Table Name
    protected $table = 'todos';
    //Primary key
    public $primaryKey = 'id';

    protected $fillable = [

        'todoable_id',
        'todoable_type',
        'title',
        'description',
        'admin_id',
        'user_id',
        'project_id',
        'priority',
        'outcome_id',
        'tasktype_id',
        'started_at',
        'due_time',
        'completed_at',
    ];

 	protected $casts = [
        'started_at' => 'datetime',
        'due_time' => 'datetime',
        'completed_at',
    ];

    protected $dates = [
        'started_at',
        'due_time',
        'completed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function setDueTimeAttribute($date)
    {

        // $attributes['due_time'] = Carbon::createFromFormat('d-m-Y H:i:s', $date);
        $this->attributes['due_time'] = Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function setStartedAtAttribute($date)
    {

        $this->attributes['started_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    // public function getCompletedAtAttribute($date)
    // {
    //     return Carbon::parse($date)->format('d-m-Y');
    // }

    public function outcome() {

        return $this->belongsTo('App\Outcome');
    }

    public function project() {

        return $this->belongsTo('App\Tbl_projects', 'project_id');
    }

    public function user() {

        return $this->belongsTo('App\User');
    }

    public function admin() {

        return $this->belongsTo('App\User');
    }

    public function tasktype() {

        return $this->belongsTo('App\Tasktype');
    }

    // public function lead() {

    //     return $this->belongsTo(Tbl_leads::class, 'lead_id');
    // }

    public function todoable()
    {
        return $this->morphTo();
    }

    public function currentDate() {

        // return Carbon::now();
        return $this->started_at;
    }

    public function checkOverdue() {

        if($this->due_time >= $this->currentDate()) {

            return "Due date: in " . $this->due_time->diffInDays() . " days";

        } else {

            return 'Due date: ' . $this->due_time->diffForHumans() . ' (overdue)';

        }
    }

    public function getPriorityAttribute($value) {

        if ($value == 1) {

            // return "<span class='dot dot-sm dot-success'></span> Low";
            return $value;
        } elseif($value == 2) {

            // return "<small class='dot dot-sm dot-warning'></small> Medium";
            return $value;
        } else {

            // return "<span class='dot dot-sm dot-danger'></span> High";
            return $value;
        }

    }


}
