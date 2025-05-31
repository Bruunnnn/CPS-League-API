<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChampionRotation extends Model
{
    protected $table = 'champion_rotations';

    protected $fillable = [
        'freeChampionIds',
        'freeChampionIdsForNewPlayers',
        'maxNewPlayerLevel',
    ];

    protected $casts = [
        'freeChampionIds' => 'array',
        'freeChampionIdsForNewPlayers' => 'array',
    ];

}
