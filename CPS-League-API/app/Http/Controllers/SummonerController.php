<?php

namespace App\Http\Controllers;

use App\Models\Ranked;
use App\Services\ChampionService;
use App\Services\MasteryService;
use App\Services\MatchHistoryService;
use App\Models\Mastery;
use App\Models\MatchHistory;
use App\Models\RankedHistory;
use App\Services\SummonerService;
use Illuminate\Support\Facades\Http;
use App\Services\RankedService;
use App\Services\ChampRotationService;


class SummonerController extends Controller
{
    protected ChampRotationService $champRotationService;
    protected ChampionService $championService;
    protected MasteryService $masteryService;
    protected MatchHistoryService $matchHistoryService;
    protected RankedService $rankedService;
    protected SummonerService $summonerService;

    public function __construct(
        ChampRotationService $champRotationService,
        ChampionService $championService,
        MasteryService $masteryService,
        MatchHistoryService $matchHistoryService,
        RankedService $rankedService,
        SummonerService $summonerService
    ) {
        $this->champRotationService = $champRotationService;
        $this->championService = $championService;
        $this->masteryService = $masteryService;
        $this->matchHistoryService = $matchHistoryService;
        $this->rankedService = $rankedService;
        $this->summonerService = $summonerService;
    }

    public function fetchDdragon()
    {
        // Returns ddragon response
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/cdn/15.13.1/data/en_US/champion.json');
        $championData = $response->json()['data'];


        return $championData;
    }


    public function show($riotId)
    {
        $summoner = $this->summonerService->storeSummoner($riotId);
        $puuid = $summoner->puuid;


        $this->champRotationService->storeChampsForNewPlayers();

        $this->championService->storeAllChampions();
        $this->rankedService->getRankedBySummonerId($summoner);
        $this->rankedService->storeRankedData($puuid);
        $this->masteryService->storeTopChampionMastery($puuid);
        $this->matchHistoryService->storeMatchHistory($puuid);


        if ($summoner instanceof \Illuminate\Http\Response) {
            // Throws error response if we get a "response" returned, then proceeds
            return $summoner;
        }
        $freeChampions = $this->champRotationService->getCurrentFreeChampions();
        // Fetch ranked data from Riot API and store/update
        //$rankedSummoner = $RankedService->getRankedBySummonerId($summoner->summoner_id);
        //$storedRanked = $RankedService->storeRankedData($puuid,$rankedSummoner);
        // Fetch mastery data from Riot API and store/update
        //$masterySummoner = $MasteryService->storeTopChampionMastery($puuid);

        // Fetch match history and store/update
        //$matchHistorySummoner = $MatchHistoryService->storeMatchHistory($puuid);


        //$ChampRotationService->storeChampsForNewPlayers();


        // Fetch saved ranked data from DB
        $rankedData = Ranked::where('puuid', $puuid)->get();



        // Create ranked maps and stats
        $rankedMap = [];
        $soloWins = $soloLosses = $flexWins = $flexLosses = 0;

        foreach ($rankedData as $ranked) {
            if ($ranked->queueType === 'RANKED_SOLO_5x5') {
                $rankedMap['solo'] = "{$ranked->tier} {$ranked->rank}";
                $soloWins += $ranked->wins ?? 0;
                $soloLosses += $ranked->losses ?? 0;
            } elseif ($ranked->queueType === 'RANKED_FLEX_SR') {
                $rankedMap['flex'] = "{$ranked->tier} {$ranked->rank}";
                $flexWins += $ranked->wins ?? 0;
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
        $rankedHistory = RankedHistory::where('puuid', $puuid)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $groupedRankedHistory = $rankedHistory->isNotEmpty()
            ? $rankedHistory->groupBy('queue_type')->map(function ($entries, $queueType) {
                return [
                    'queue_type' => $queueType,
                    'win_rates' => $entries->sortBy('created_at')->pluck('win_rate')->values(),
                ];
            })->values()
            : collect();

        $queueMap = $this->summonerService->getQueueMappings();

        // Fetch stored match history & mastery
        $matchHistory = MatchHistory::where('puuid', $puuid)->orderByDesc('endGameTimestamp')->take(30)->get();
        $groupedMatches = $matchHistory->map(function ($match){
            $players = MatchHistory::where('gameId',$match->gameId)->get();
            $isRemake = isset($match->gameDuration) && $match->gameDuration < 210;
            return [
                'match' => $match,
                'players' => $players,
                'wins'=>$match->win,
                'remake' => $isRemake,
            ];
        });

        $masteries = Mastery::where('puuid', $puuid)
            ->orderByDesc('championPoints')
            ->get();

        // Fetch champion list from DDragon
        $championData = $this->fetchDdragon();
        $championMap = [];
        foreach ($championData as $champion) {
            $championMap[(int)$champion['key']] = [
                'name' => $champion['id'],
                'image' => "https://ddragon.leagueoflegends.com/cdn/15.13.1/img/champion/{$champion['id']}.png"
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

        // Add recently played with table:
        $recentlyPlayedWith = collect();
        // iterate through grouped matches to find players:
        foreach ($groupedMatches as $game) {
            $players = $game['players'];
            $gameWon = $game['wins'] == true;
            foreach ($players as $player) {
                // If the player is not the currently searched summoner (Your name), list them
                if ($player->puuid !==$puuid){
                    $name = $player->riotIdGameName;
                    $tagline = $player->riotIdTagline;
                    $key = $player->riotIdGameName . '#' .  $player->riotIdTagline;
                    $icon = $player->profileIcon;
                    $data = $recentlyPlayedWith->get($key, [
                        'name' => $name,
                        'tagline' => $tagline,
                        'count' => 0,
                        'wins' => 0,
                        'losses' => 0,
                        'icon'=>$icon,
                    ]);

                    $data['count']++;
                    $data[$gameWon ? 'wins' : 'losses']++;

                    if (!isset($data['profileIcon'])) {
                        $data['profileIcon'] = $icon;
                    }
                    if(!isset($data['tagline'])) {
                        $data['tagline'] = $tagline;
                    }

                    $recentlyPlayedWith[$key] = $data;
                }
            }
        }
        // Count players appearances
        $recentlyPlayedWith = collect($recentlyPlayedWith)
            // If a player is found more than once
            ->filter(fn($player)=>$player['count']>2)
            ->sortByDesc(fn($player)=>$player['count'])
            ->take(20);

        // We know that hardcoding this is not the right way, but Riot themselves have made us need to do it like this:.
        // This is beacause in riots jSon the summonerspell comes out as an integer, but we need the string name from them
        // to get the images, so we have converted them here:
        $summonerSpellMap = [
            1 => 'SummonerBoost',
            3 => 'SummonerExhaust',
            4 => 'SummonerFlash',
            6 => 'SummonerHaste',
            7 => 'SummonerHeal',
            11 => 'SummonerSmite',
            12 => 'SummonerTeleport',
            13 => 'SummonerMana',
            14 => 'SummonerDot',
            21 => 'SummonerBarrier',
            30 => 'SummonerPoroRecall',
            31 => 'SummonerPoroThrow',
            32 => 'SummonerSnowball',
            39 => 'SummonerSnowURFSnowball_Mark',
            54 => 'Summoner_UltBookPlaceholder',
            55 => 'Summoner_UltBookSmitePlaceholder',
            2201 => 'SummonerCherryHold',
            2202 => 'SummonerCherryFlash',
        ];

        // return response()->json(
        // return view('frontpage',
        return view('frontpage', [
            'summoner' => $summoner,
            'rankedMap' => $rankedMap,
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
            'matches' => $groupedMatches,
            'recentlyPlayedWith'=> $recentlyPlayedWith,
            'groupedRankedHistory' => $groupedRankedHistory,
            'freeChampions' => $freeChampions,
            'summonerSpellMap' => $summonerSpellMap,
            'matchHistory' => $matchHistory

        ]);
    }
}
