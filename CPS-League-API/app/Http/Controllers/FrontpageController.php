<?php

namespace App\Http\Controllers;

use App\Models\Ranked;
use App\Models\Summoner;
use Illuminate\Http\Request;

class FrontpageController extends Controller
{
    public function index(Request $request)
    {
        $puuid = $request->input('puuid', '6mhRxZFxNMCIA3aACd1lopxXFcN_OWebSyvHKsnHNsIlu_rAT9hn17XjCTzQoo01J9-MUGQ9iuDWDw');

        if (empty($puuid)) {
            return response()->view('errors.custom', ['message' => 'No PUUID provided.'], 400);
        }

        // Fetch summoner data from DB
        $summoner = Summoner::where('puuid', $puuid)->first();

        if (!$summoner) {
            return response()->view('errors.custom', ['message' => 'Summoner not found.'], 404);
        }

        // Fetch ranked data from DB
        $rankedData = Ranked::where('puuid', $puuid)->get();

        // Map queue types
        $rankedMap = [];
        foreach ($rankedData as $ranked) {
            if ($ranked->queueType === 'RANKED_SOLO_5x5') {
                $rankedMap['solo'] = "{$ranked->tier} {$ranked->rank}";
            } elseif ($ranked->queueType === 'RANKED_FLEX_SR') {
                $rankedMap['flex'] = "{$ranked->tier} {$ranked->rank}";
            }
        }



        // Return view with both rankedMap and summoner
        return view('frontpage', [
            'rankedMap' => $rankedMap,
            'summoner' => $summoner
        ]);
    }
}
