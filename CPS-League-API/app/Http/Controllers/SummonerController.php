<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiotService;


class SummonerController extends Controller {
    public function show($riotId, RiotService $riotService) {
        $parts = explode('-', $riotId);
        if (count($parts) != 2) {
            return response("Invalid riot ID format", 400);
        }
        [$gameName,$tagLine] = $parts;
        $account = $riotService->getSummonerByName($gameName, $tagLine);
        if (!isset($account['puuid'])) {
            return response("Summoner not found", 404);
        }
        $matches = $riotService->getMatchHistory($account['puuid'], 5);
        return response()->json([
            //'account' => $account,
            'matches' => $matches
        ]);
    }
}
