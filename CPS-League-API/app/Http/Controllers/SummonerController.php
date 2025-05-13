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
    private function getQueueMappings()
    {
        $response = Http::withoutVerifying()->get('https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/queues.json');

        if ($response->successful()) {
            $queues = $response->json();

            // Map queueId to description
            $queueMap = [];
            foreach ($queues as $queue) {
                $queueMap[$queue['id']] = $queue['shortName'] ?? 'Unknown';
            }

            return $queueMap;
        }

        return [];
    }

    public function summonerJson($riotId, RiotService $riotService) {
        // Returns relevant functions for fetching puuid and summoner
        // Returns Json summoner
        $parts = explode('-', $riotId);
        if (count($parts) !== 2) {
            return response("Invalid riot ID format", 400);
        }
        [$gameName, $tagLine] = $parts;

        // Fetch account info from Riot API
        $account = $riotService->getSummonerByName($gameName, $tagLine);
        if (!isset($account['puuid'])) {
            return response("Summoner not found, or API-key not set", 404);
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

    public function rankedJson(string $puuid, RiotService $riotService)
    {
        // Returns Json ranked
        $summoner = Summoner::where('puuid', $puuid)->firstOrFail();
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
        return $rankedSummoner;
    }

    public function masteryJson(string $puuid, RiotService $riotService)
    {
        // Returns Json mastery
        $masteryInfo = $riotService->getChampionMastery($puuid);
        $topMastery = array_slice($masteryInfo, 0, 30);
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
        return $topMastery;
    }

    public function matchHistoryJson(String $puuid, RiotService $riotService)
    {
        // Returns Json matchhistory
        $matches = $riotService->getMatchHistory($puuid, 15);
        foreach ($matches as $match) {
            foreach ($match['info']['participants'] as $participant) {
                $participantPuuid = $participant['puuid'];
//                // Try to get the summoner from DB first (cached)
//                $summoner = Summoner::where('puuid', $participantPuuid)->first();
//                // If not found in DB, fetch from Riot API and store it
//                if (!$summoner) {
//                    $summonerInfo = $riotService->getSummonerByPuuid($participantPuuid);
//                    if (!isset($summonerInfo['id'])) {
//                        continue; // skip if data is invalid
//                    }
//
//                    $summoner = Summoner::updateOrCreate(
//                        ['puuid' => $participantPuuid],
//                        [
//                            'game_name' => $participant['riotIdGameName'] ?? 'Unknown',
//                            'tag_line' => $participant['riotIdTagline'] ?? '',
//                            'summoner_id' => $summonerInfo['id'],
//                            'account_id' => $summonerInfo['accountId'],
//                            'profile_icon_id' => $summonerInfo['profileIconId'],
//                            'summoner_level' => $summonerInfo['summonerLevel'],
//                        ]
//                    );
//                }
                MatchHistory::updateOrCreate(
                    [
                        'puuid' => $participant['puuid'],
                        'gameId' => $match['info']['gameId'],
                    ],
                    [
                        'mapId' => $match['info']['mapId'],
                        'queueId' => $match['info']['queueId'] ?? 0,
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
//                        'profile_icon_id' =>$summoner->profile_icon_id,
                    ]
                );
            }
        }
        return $matches;
    }

    public function returnJson($riotId, RiotService $riotService)
    {
        // Returns all json methods for /api/summoner/riotId
        $summoner = $this->summonerJson($riotId,$riotService);
        if ($summoner instanceof \Illuminate\Http\Response) {
            // Handle error response early (or rethrow/return it)
            return $summoner;
        }
        $puuid = $summoner->puuid;
        $rankedSummoner = $this->rankedJson($puuid,$riotService);
        $topMastery = $this->masteryJson($puuid,$riotService);
        $matchHistory = $this->matchHistoryJson($puuid,$riotService);
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/cdn/15.9.1/data/en_US/champion.json');

        return response()->json([
            'summoner' => $summoner,
            'rankedSummoner'=>$rankedSummoner,
            'topMastery'=>$topMastery,
            'matchHistory'=>$matchHistory,
            'championData'=>$response
        ]);
    }

    public function fetchDdragon()
    {
        // Returns ddragon response
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/cdn/15.9.1/data/en_US/champion.json');
        $championData = $response->json()['data'];
        return $championData;
    }

    public function show($riotId, RiotService $riotService)
    {
       $summoner = $this->summonerJson($riotId,$riotService);
        if ($summoner instanceof \Illuminate\Http\Response) {
            // Throws error response if we get a "response" returned, then proceeds
            return $summoner;
        }
        $puuid = $summoner->puuid;
        //dd($puuid);
        // Fetch ranked data from Riot API and store/update
        $this->rankedJson($puuid,$riotService);

        // Fetch mastery data from Riot API and store/update
        $this->masteryJson($puuid,$riotService);

        // Fetch match history and store/update
        $this->matchHistoryJson($puuid,$riotService);

        // Fetch saved ranked data from DB
        $rankedData = Ranked::where('puuid', $puuid)->get();

        // From the player entered, get the latest 5 entries sorted form oldest to newest
        $latestMatches = MatchHistory::where('puuid', $puuid)
            ->orderByDesc('endGameTimestamp')
            ->take(5)
            ->get()
            ->sortBy('endGameTimestamp');



        // Pluck the date from the latestRanked entries
        // Pluck the values from the entries and add the total-games played with wins and losses
        // return the total games where entries that the player have won, is calculated in percentages

        // The matchLabels shows the timestamp for the given match
        $matchLabels = $latestMatches->map(fn($match) => date('Y-m-d',$match->endGameTimeStamp / 1000));

        // matchWinValues shows your skills for your latest 5 matches - or your skill-issue
        $matchWinValues = $latestMatches->map(fn($match) => $match->win ? 1 : 0);

        //$graphValues = $latestRanked->map(function ($entry) {
        //    $totalGames = $entry->win + $entry->losses;
        //    return $totalGames > 0 ? round(($entry->win / $totalGames) * 100, 2) : 0;
        //});



        $wins = $rankedData['wins'] ?? 0;                 // Never used??? maybe used anyways, so care!


        // Create ranked maps and stats
        $rankedMap = [];
        $soloWins = $soloLosses = $flexWins = $flexLosses = 0;

        foreach ($rankedData as $ranked) {
            if ($ranked->queueType === 'RANKED_SOLO_5x5') {
                $rankedMap['solo'] = "{$ranked->tier} {$ranked->rank}";
                // soloWins adds the ranked data tables win column
                // Same applies for the soloLosses
                $soloWins += $ranked->win ?? 0;
                $soloLosses += $ranked->losses ?? 0;
            } elseif ($ranked->queueType === 'RANKED_FLEX_SR') {
                $rankedMap['flex'] = "{$ranked->tier} {$ranked->rank}";
                $flexWins += $ranked->win ?? 0;
                $flexLosses += $ranked->losses ?? 0;
            }
        }

        $totalSoloGames = $soloWins + $soloLosses;
        $totalFlexGames = $flexWins + $flexLosses;

        $soloWinratePercent = $totalSoloGames > 0 ? ($soloWins / $totalSoloGames) * 100 : 0;
        $flexWinratePercent = $totalFlexGames > 0 ? ($flexWins / $totalFlexGames) * 100 : 0;


        // Store new ranked history snapshot if there's new data

        foreach ([
            'solo' => ['wins' => $soloWins, 'losses' => $soloLosses, 'win_rate' => $soloWinratePercent, 'queue' => 'RANKED_SOLO_5x5'],
            'flex' => ['wins' => $flexWins, 'losses' => $flexLosses, 'win_rate' => $flexWinratePercent, 'queue' => 'RANKED_FLEX_SR'],
            ] as $data) {
            if ($data['wins'] + $data['losses'] === 0) {
                continue;
            }

            // Fetch what rank type it is, and then place that into the "rank" in the ranked_history model:
            $queueRanks = [];

            foreach (['RANKED_SOLO_5x5', 'RANKED_FLEX_SR'] as $queueType) {
                $ranked = $rankedData->firstWhere('queueType', $queueType);
                if ($ranked) {
                    $queueRanks[$queueType] = "{$ranked->tier} {$ranked->rank}";
                } else {
                    $queueRanks[$queueType] = null;
                }
            }
            $latest = RankedHistory::where('puuid', $puuid)
                ->where('queue_type', $data['queue'])
                ->latest()
                ->first();

            // Checks if current total is somehow larger than the latest total
            $latestTotal = $latest ? ($latest->wins + $latest->losses) : 0;
            $currentTotal = $data['wins'] + $data['losses'];

            // If that is the case, create the new model:
            if ($currentTotal > $latestTotal) {
                RankedHistory::create([
                    'puuid' => $puuid,
                    'queue_type' => $data['queue'],
                    'rank' => $queueRanks[$data['queue']],
                    'wins' => $data['wins'],
                    'losses' => $data['losses'],
                    'win_rate' => $data['win_rate'],
                ]);
            }
        }
        $queueMap = $this->getQueueMappings();

        // Fetch stored match history & mastery
        $matchHistory = MatchHistory::where('puuid', $puuid)->orderByDesc('endGameTimestamp')->take(10)->get();
        $groupedMatches = $matchHistory->map(function ($match){
            $players = MatchHistory::where('gameId',$match->gameId)->get();
            return [
                'match' => $match,
                'players' => $players,
            ];
        });

        // Groups matches with the gameId
//        $groupedMatches = $matchHistory->groupBy('gameId');
//        $gameIds = $matchHistory->pluck('gameId');
//        $allPlayers = MatchHistory::whereIn('gameId', $gameIds)->get()->groupBy('gameId');




        $masteries = Mastery::where('puuid', $puuid)
            ->orderByDesc('championPoints')
            ->get();


        // Fetch champion list from DDragon
        $championData = $this->fetchDdragon();
        $championMap = [];
        foreach ($championData as $champion) {
            $championMap[(int)$champion['key']] = [
                'name' => $champion['id'],
                'image' => "https://ddragon.leagueoflegends.com/cdn/15.9.1/img/champion/{$champion['id']}.png"
            ];
        }

        // Map mastery data with champion info
        $masteryCards = $masteries->map(function ($mastery) use ($championMap) {
            $champion = $championMap[$mastery->championId]?? ['name' => 'Unknown', 'image' => ''];
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
            'queueMap' => $queueMap,
            'masteryCards' => $masteryCards,
            'championMap' => $championMap,
//            'groupedMatches'=>$groupedMatches,
//            'allPlayers' => $allPlayers,
            'matches' => $groupedMatches,
            'matchLabels' => $matchLabels,
            'matchWinValues' => $matchWinValues,

        ]);


    }
}
