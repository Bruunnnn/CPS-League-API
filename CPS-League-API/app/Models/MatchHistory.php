<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHistory extends Model
{
    protected $table = 'matchHistory';
    protected $fillable = [
        'gameId',
        'puuid',
        'mapId',
        'queueId',
        'endGameTimestamp',
        'win',
        'riotIdGameName',
        'gameDuration',
        'championId',
        'kills',
        'deaths',
        'assists',
        'totalMinionsKilled',
        'totalEnemyJungleMinionsKilled',
        'item0',
        'item1',
        'item2',
        'item3',
        'item4',
        'item5',
        'item6',
        'summoner1Id',
        'summoner2Id',
        'profile_icon_id',
    ];
    public function getQueueTypeAttribute()
    {
        return match ($this->queueId) {
            420 => 'RANKED_SOLO_5x5',
            440 => 'RANKED_FLEX_SR',
            default => 'Unranked',
        };
    }
}
