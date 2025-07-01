<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranked extends Model
{
    protected $table = 'ranked';
    protected $fillable = [
        'puuid',
        'queueType',
        'tier',
        'rank',
        'leaguePoints',
        'wins',
        'losses',
    ];
}
