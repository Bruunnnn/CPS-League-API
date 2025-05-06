<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RankedHistory extends Model
{
    protected $table = 'ranked_history';
    protected $fillable = [
        'puuid',
        'queue_type',
        'rank',
        'wins',
        'losses',
        'win_rate',
        ];
    //
    public function summoner() : BelongsTo
    {
        return $this->belongsTo(summoner::class, 'puuid', 'puuid');
    }

    /*
    public function winRateGraph($puuid) {
        $history = RankedHistory::where('puuid', $puuid)
            ->orderBy('created_at', 'asc')
            ->get(['created_at', 'win_rate']);

        return view('ranked.winrategraph',)
    }
    */

}
