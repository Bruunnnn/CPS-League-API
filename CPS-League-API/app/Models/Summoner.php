<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summoner extends Model
{
    protected $fillable = [
        'puuid',
        'game_name',
        'tag_line',
        'summoner_id',
        'account_id',
        'profile_icon_id',
        'summoner_level',
    ];
}
