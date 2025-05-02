<?php

namespace App\Http\Controllers;

use App\Models\Ranked;
use App\Services\RiotService;
use App\Models\Summoner;
use App\Models\Mastery;
use App\Models\MatchHistory;


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

        // summoner Json:
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
        $topMastery = array_slice($masteryinfo, 0, 30);

        // mastery Json:
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
        $matches = $riotService->getMatchHistory($account['puuid'], 30); // or count you want

        foreach ($matches as $match) {
            foreach ($match['info']['participants'] as $participant) {
                $matchHistory[] = MatchHistory::updateOrCreate(
                    [
                        'puuid' => $participant['puuid'],
                        'gameId' => $match['info']['gameId'],
                    ],
                    [
                        'mapId' => $match['info']['mapId'],
                        'endGameTimestamp' => $match['info']['gameEndTimestamp'],
                        'win' => $participant['win'],
                        'riotIdGameName' => $participant['riotIdGameName'],
                        'gameDuration' => $match['info']['gameDuration'],
                        'championId' => $participant['championId'],
                        'kills' => $participant['kills'] ?? 0,
                        'deaths' => $participant['deaths'] ?? 0,
                        'assists' => $participant['assists'] ?? 0,
                        'totalMinionsKilled' => $participant['totalMinionsKilled'] ?? 0,
                        'totalEnemyJungleMinionsKilled' => $participant['totalEnemyJungleMinionsKilled'] ?? 0,
                        'item0' => $participant['item0'] ?? 0,
                        'item1' => $participant['item1'] ?? 0,
                        'item2' => $participant['item2'] ?? 0,
                        'item3' => $participant['item3'] ?? 0,
                        'item4' => $participant['item4'] ?? 0,
                        'item5' => $participant['item5'] ?? 0,
                        'item6' => $participant['item6'] ?? 0,
                        'summoner1Id' => $participant['summoner1Id'] ?? 0,
                        'summoner2Id' => $participant['summoner2Id'] ?? 0,
                    ]
                );
            }
        }

        //Ranked Json
        $ranked = [];

        for ($i = 0; $i < count($rankedsummoner); $i++) {
            $ranked[] = Ranked::updateOrCreate(
                [
                    'puuid' => $summonerInfo['puuid'],
                    'queueType' => $rankedsummoner[$i]['queueType'], // use queueType as unique per queue
                ],
                [
                    'tier' => $rankedsummoner[$i]['tier']?? 'UNRANKED',
                    'rank' => $rankedsummoner[$i]['rank']?? '-',
                    'win' => $rankedsummoner[$i]['wins']?? 0,
                    'losses' => $rankedsummoner[$i]['losses']?? 0,
                ]
            );
        }


        return response()->json([
            'summoner' => $summoner,
            'ranked' => $ranked,
            'mastery' => $mastery,
            'matches' => $matchHistory,
        ]);

    }
}
