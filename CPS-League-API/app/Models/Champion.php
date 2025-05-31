<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    protected $fillable = [
        'key',
        'name',
        'title',
        'blurb',
        'image_url',
    ];
    //
}
