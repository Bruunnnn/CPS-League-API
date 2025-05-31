<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChampionRotation extends Model
{
    protected $table = 'champion_rotation';
    protected $fillable = [
        'puuid',
        'freeChampionsIdsForNewPlayers',
        'maxNewPlayerLevel',
    ];

    protected $casts = [
        'freeChampionsIdsForNewPlayers' => 'array',
    ];

}
