<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summoner extends Model
{
    protected $fillable = [
        'puuid',
        'gameName',
        'tagLine',
        'profileIconId',
        'summonerLevel',
        'revisionDate',
    ];
}
