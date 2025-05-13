<?php

namespace App\Http\Controllers;

use App\Models\RankedHistory;
use Illuminate\Http\Request;
use App\Models\Ranked;

class graphController extends Controller
{
    public function index()
    {
        // fetches 10 data points in the order from oldest to newest
        // Check back later and try change order from newest to oldest (change desc to assc?)
        $puuid = auth()->user()->summoner->puuid;
        $data = RankedHistory::where($puuid, $puuid)
            ->where('queue_type', 'RANKED_SOLO_5x5')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->sortBy('created_at');

        // Think about the logic in this. Should some of the columns be other values from the table?
        $labels = $data->pluck('created_at')->map(fn ($date) => $date->format('Y-m-d'));

        // Denne value hentes sandsynligvis ikke rigtigt.
        $values = $data->pluck('win_rate');

        return view('partials.graph', compact('labels', 'values'));
    }
}
