<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHistory extends Model
{
    protected $fillable = [
        'puuid',
        'mapId',
        'endGameTimestamp',
        'win',
        'gameDuraction',
        'championId',
        'kills',
        'deaths',
        'assists',
        'totalMinionsKilled',
        'enemyJungleMonsterkills',
        'item0',
        'item1',
        'item2',
        'item3',
        'item4',
        'item5',
        'item6',
        'summoner1Id',
        'summoner2Id',
    ];
}
