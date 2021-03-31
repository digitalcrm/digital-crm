<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'file_name',
        'mime_type',
        'file_path',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function imageUrl() 
    {
        return $this->file_path 
            ? asset('public/storage/'.$this->file_path)
            : '';
    }

    public function fileType()
    {
        $value = Str::of($this->mime_type)->match('/image/');
        if ($value == "image") {
            return true;
        }
    }
}
