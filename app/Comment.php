<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'message',
        'admin_id',
        'user_id',
        'is_active',
    ];

    /**
     * Get the owning commentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
