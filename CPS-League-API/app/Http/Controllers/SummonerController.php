<?php

namespace App\Http\Controllers;

use App\Models\Ranked;
use App\Services\RiotService;
use App\Models\Summoner;
use App\Models\Mastery;
use App\Models\MatchHistory;
use function Webmozart\Assert\Tests\StaticAnalysis\length;


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

        // summoner DB:
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


        $rankedsummoner = $riotService->getRankedBySummonerId($summoner['summoner_id']);

        $masteryinfo = $riotService->getChampionMastery($account['puuid']);

        $topMastery = array_slice($masteryinfo, 0, 20);

        // mastery DB:
        $mastery = [];
        for ($i=0; $i <count($topMastery); $i++) {

            $mastery[] = Mastery::updateOrCreate(
                ['puuid' => $summonerInfo['puuid'],
                    'championId' => $topMastery[$i]['championId'],
                    ],

                [
                    'championLevel' => $topMastery[$i]['championLevel'],
                    'championPoints' => $topMastery[$i]['championPoints'],
                    'lastPlayTime' => $topMastery[$i]['lastPlayTime'],
                    'championPointsSinceLastLevel' => $topMastery[$i]['championPointsSinceLastLevel'],
                    'championPointsUntilNextLevel' => $topMastery[$i]['championPointsUntilNextLevel'],
                ]
            );
        }

        $rankedEntries = [];

        for ($i = 0; $i < count($rankedsummoner); $i++) {
            $ranked[] = Ranked::updateOrCreate(
                [
                    'puuid' => $summonerInfo['puuid'],
                    'queueType' => $rankedsummoner[$i]['queueType'], // use queueType as unique per queue
                ],
                [
                    'tier' => $rankedsummoner[$i]['tier'],
                    'rank' => $rankedsummoner[$i]['rank'],
                    'win' => $rankedsummoner[$i]['wins'],
                    'losses' => $rankedsummoner[$i]['losses'],
                ]
            );
        }


        $matches = $riotService->getMatchHistory($account['puuid'], 1);
        return response()->json([
            'summoner' => $summoner,
            'ranked' => $ranked,
            'mastery' => $mastery,
            'matches' => $matches
        ]);
    }
}
