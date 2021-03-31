<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_search_keywords extends Model
{
    use HasFactory;

    //Table Name
    protected $table = 'tbl_search_keywords';
    //Primary key
    public $primaryKey = 'sk_id';
    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword', 'counter', 'results'
    ];
}
