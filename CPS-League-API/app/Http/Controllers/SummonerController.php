<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiotService;
use App\Models\Summoner;



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

        $summonerInfo = $riotService->getSummonerByPuuid($account['puuid']);

        // DB:
        $summoner = Summoner::updateOrCreate(
            ['puuid' => $summonerInfo['puuid']],  // Match the summoner using puuid
            [
                'game_name' => $account['gameName'],         // In-game name (gameName)
                'tag_line' => $account['tagLine'],      // Summoner tagLine
                'summoner_id' => $summonerInfo['id'],   // Summoner's ID
                'account_id' => $summonerInfo['accountId'],  // Account ID
                'profile_icon_id' => $summonerInfo['profileIconId'], // Profile Icon ID
                'summoner_level' => $summonerInfo['summonerLevel'],  // Summoner's level
            ]
        );

        $ranked = $riotService->getRankedBySummonerId($summoner['summoner_id']);

        $mastery = $riotService->getChampionMastery($summoner['puuid']);
        //set the number of champions to get the mastery from
        $topMastery = array_slice($mastery, 0, 5);

        $matches = $riotService->getMatchHistory($account['puuid'], 5);
        return response()->json([
            'summoner' => $summoner,
            'summonerInfo' => $summonerInfo,
            'account' => $account,
            'ranked' => $ranked,
            'mastery' => $topMastery,
            'matches' => $matches
        ]);
    }
}
