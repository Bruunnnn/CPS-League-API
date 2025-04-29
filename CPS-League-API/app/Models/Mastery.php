<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mastery extends Model
{
    protected $fillable = [
        'puuid',
        'championId',
        'championLevel',
        'championPoints',
        'lastPlayTime',
        'championPointsSinceLastLevel',
        'championPointsUntilNextLevel',
    ];
}
