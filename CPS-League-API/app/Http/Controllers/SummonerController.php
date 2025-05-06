<?php

namespace App\Http\Controllers;

use App\Models\Ranked;
use App\Services\RiotService;
use App\Models\Summoner;
use App\Models\Mastery;
use App\Models\MatchHistory;
use App\Models\RankedHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SummonerController extends Controller
{
    public function summonerJson($riotId, RiotService $riotService) {
        $parts = explode('-', $riotId);
        if (count($parts) !== 2) {
            return response("Invalid riot ID format", 400);
        }
        [$gameName, $tagLine] = $parts;

        // Fetch account info from Riot API
        $account = $riotService->getSummonerByName($gameName, $tagLine);
        if (!isset($account['puuid'])) {
            return response("Summoner not found", 404);
        }

        $puuid = $account['puuid'];
        $summonerInfo = $riotService->getSummonerByPuuid($puuid);

        // Store or update summoner in DB
        $summoner = Summoner::updateOrCreate(
            ['puuid' => $puuid],
            [
                'game_name' => $account['gameName'],
                'tag_line' => $account['tagLine'],
                'summoner_id' => $summonerInfo['id'],
                'account_id' => $summonerInfo['accountId'],
                'profile_icon_id' => $summonerInfo['profileIconId'],
                'summoner_level' => $summonerInfo['summonerLevel'],
            ]
        );
        return $summoner;
    }
    public function show($riotId, RiotService $riotService)
    {
       $summoner = $this->summonerJson($riotId,$riotService);

        $puuid = $summoner["puuid"];
        //dd($puuid);
        // Fetch ranked data from Riot API and store/update
        $rankedSummoner = $riotService->getRankedBySummonerId($summoner->summoner_id);
        foreach ($rankedSummoner as $rankedEntry) {
            Ranked::updateOrCreate(
                [
                    'puuid' => $puuid,
                    'queueType' => $rankedEntry['queueType'],
                ],
                [
                    'tier' => $rankedEntry['tier'] ?? 'UNRANKED',
                    'rank' => $rankedEntry['rank'] ?? '-',
                    'win' => $rankedEntry['wins'] ?? 0,
                    'losses' => $rankedEntry['losses'] ?? 0,
                ]
            );
        }

        // Fetch mastery data from Riot API and store/update
        $masteryInfo = $riotService->getChampionMastery($puuid);
        $topMastery = array_slice($masteryInfo, 0, 20);
        foreach ($topMastery as $entry) {
            Mastery::updateOrCreate(
                [
                    'puuid' => $puuid,
                    'championId' => $entry['championId'],
                ],
                [
                    'championLevel' => $entry['championLevel'],
                    'championPoints' => $entry['championPoints'],
                    'lastPlayTime' => $entry['lastPlayTime'],
                    'championPointsSinceLastLevel' => $entry['championPointsSinceLastLevel'],
                    'championPointsUntilNextLevel' => $entry['championPointsUntilNextLevel'],
                ]
            );
        }

        // Fetch match history and store/update
        $matches = $riotService->getMatchHistory($puuid, 15);
        foreach ($matches as $match) {
            foreach ($match['info']['participants'] as $participant) {
                MatchHistory::updateOrCreate(
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

        // Fetch saved ranked data from DB
        $rankedData = Ranked::where('puuid', $puuid)->get();

        $wins = $rankedData['wins'] ?? 0;



        $rankedMap = [];
        foreach ($rankedData as $ranked) {
            if ($ranked->queueType === 'RANKED_SOLO_5x5') {
                $rankedMap['solo'] = "{$ranked->tier} {$ranked->rank}";
            } elseif ($ranked->queueType === 'RANKED_FLEX_SR') {
                $rankedMap['flex'] = "{$ranked->tier} {$ranked->rank}";
            }
        }
        $rankedData = Ranked::where('puuid', $puuid)->get();

        $soloWins = 0;
        $soloLosses = 0;
        $flexWins = 0;
        $flexLosses = 0;

        foreach ($rankedData as $ranked) {
            if ($ranked->queueType === 'RANKED_SOLO_5x5') {
                $soloWins += $ranked->win ?? 0;
                $soloLosses += $ranked->losses ?? 0;
            } elseif ($ranked->queueType === 'RANKED_FLEX_SR') {
                $flexWins += $ranked->win ?? 0;
                $flexLosses += $ranked->losses ?? 0;
            }
        }

        $totalSoloGames = $soloWins + $soloLosses;
        $totalFlexGames = $flexWins + $flexLosses;

        $soloWinratePercent = $totalSoloGames > 0 ? ($soloWins / $totalSoloGames) * 100 : 0;
        $flexWinratePercent = $totalFlexGames > 0 ? ($flexWins / $totalFlexGames) * 100 : 0;


        // Combines both the queues into a single loop

        foreach ([
            'solo' => ['wins' => $soloWins, 'losses' => $soloLosses, 'win_rate' => $soloWinratePercent, 'queue' => 'RANKED_SOLO_5x5'],
            'flex' => ['wins' => $flexWins, 'losses' => $flexLosses, 'win_rate' => $flexWinratePercent, 'queue' => 'RANKED_FLEX_SR'],
            ] as $type => $data) {
            if ($data['wins'] + $data['losses'] === 0) {
                continue;
            }

            $latest = RankedHistory::where('puuid', $puuid)
                ->where ('queue_type', $data['queue'])
                ->latest()
                ->first();

            $isNewData = !$latest ||
                $latest->wins !== $data['wins'] ||
                $latest->losses !== $data['losses'] ||
                $latest->win_rate !== $data['win_rate'];

            if ($isNewData) {
                RankedHistory::create([
                    'puuid' => $puuid,
                    'queue_type' => $data['queue'],
                    'rank' => "{$ranked->tier} {$ranked->rank}",
                    'wins' => $data['wins'],
                    'losses' =>$data ['losses'],
                    'win_rate' =>$data['win_rate'],
                ]);
            }
        }



        // Fetch saved match history
        $matchHistory = MatchHistory::where('puuid', $puuid)->orderByDesc('endGameTimestamp')->take(10)->get();

        $masteries = Mastery::where('puuid', $puuid)
            ->orderByDesc('championPoints')
            ->get();


        // Fetch champion list from ddragon
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/cdn/14.8.1/data/en_US/champion.json');
        $championData = $response->json()['data'];

        $championMap = [];
        foreach ($championData as $champion) {
            $championMap[(int)$champion['key']] = [
                'name' => $champion['id'],
                'image' => "https://ddragon.leagueoflegends.com/cdn/14.8.1/img/champion/{$champion['id']}.png"
            ];
        }

        // Map mastery data with champion info
        $masteryCards = $masteries->map(function ($mastery) use ($championMap) {
            $champion = $championMap[$mastery->championId];
            return [
                'championName' => $champion['name'],
                'championImage' => $champion['image'],
                'championLevel' => $mastery->championLevel,
                'championPoints' => $mastery->championPoints,
                'championPointsSinceLastLevel' => $mastery->championPointsSinceLastLevel,
                'championPointsUntilNextLevel' => $mastery->championPointsUntilNextLevel,
                'lastPlayTime' => $mastery->lastPlayTime,
            ];
        });

        return view('frontpage', [
            'summoner' => $summoner,
            'rankedMap' => $rankedMap,
            'matchHistory' => $matchHistory,
            'rankedData' => $rankedData,
            'soloWins' => $soloWins,
            'soloLosses' => $soloLosses,
            'totalSoloGames' => $totalSoloGames,
            'soloWinratePercent' => $soloWinratePercent,
            'flexWins' => $flexWins,
            'flexLosses' => $flexLosses,
            'totalFlexGames' => $totalFlexGames,
            'flexWinratePercent' => $flexWinratePercent,
            'masteryCards' => $masteryCards
        ]);


    }
}
