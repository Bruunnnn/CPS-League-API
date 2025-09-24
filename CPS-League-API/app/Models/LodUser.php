<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LodUser extends Model
{
    protected $fillable = [
        'email',
        'password',
        'summoner',
    ];
}
