<?php

namespace App\Http\Controllers;

use App\Models\Ranked;
use App\Services\ChampionService;
use App\Services\MatchHistoryService;
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
    protected MatchHistoryService $matchHistoryService;
    protected RankedService $rankedService;
    protected SummonerService $summonerService;

    public function __construct(
        ChampRotationService $champRotationService,
        ChampionService $championService,
        MatchHistoryService $matchHistoryService,
        RankedService $rankedService,
        SummonerService $summonerService
    ) {
        $this->champRotationService = $champRotationService;
        $this->championService = $championService;
        $this->matchHistoryService = $matchHistoryService;
        $this->rankedService = $rankedService;
        $this->summonerService = $summonerService;
    }

    public function fetchDdragon()
    {
        // Returns ddragon response
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/cdn/15.10.1/data/en_US/champion.json');
        $championData = $response->json()['data'];
        return $championData;
    }

    public function show($riotId)
    {
        $summoner = $this->summonerService->storeSummoner($riotId);
        $puuid = $summoner->puuid;

        $this->champRotationService->storeChampsForNewPlayers();
        $this->championService->storeAllChampions();
        $this->rankedService->getRankedBySummonerId($summoner->summoner_id);
        $this->rankedService->storeRankedData($puuid);
        $this->matchHistoryService->storeMatchHistory($puuid);

        $freeChampions = $this->champRotationService->getCurrentFreeChampions();
        if ($summoner instanceof \Illuminate\Http\Response) {
            // Throws error response if we get a "response" returned, then proceeds
            return $summoner;
        }
        // Fetch saved ranked data from DB
        $rankedData = Ranked::where('puuid', $puuid)->get();

        // Create ranked maps and stats
        $rankedMap = [];
        $soloWins = $soloLosses = $flexWins = $flexLosses = 0;
        foreach ($rankedData as $ranked) {
            if ($ranked->queueType === 'RANKED_SOLO_5x5') {
                $rankedMap['solo'] = "{$ranked->tier} {$ranked->rank}";
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
            $rankedHistory = RankedHistory::where('puuid', $puuid)
                ->orderByDesc('created_at')
                ->take(10)
                ->get();
            $groupedRankedHistory = $rankedHistory->groupBy('queue_type')->map(function ($entries, $queueType){
                return[
                    'queue_type' => $queueType,
                    'win_rates' => $entries
                        ->sortBy('created_at')
                    ->pluck('win_rate')
                    ->values(),
                ];
            })->values();

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

        $queueMap = $this->summonerService->getQueueMappings();
        // Fetch stored match history & mastery
        $matchHistory = MatchHistory::where('puuid', $puuid)->orderByDesc('endGameTimestamp')->take(20)->get();
        $groupedMatches = $matchHistory->map(function ($match){
            $players = MatchHistory::where('gameId',$match->gameId)->get();
            return [
                'match' => $match,
                'players' => $players,
                'win'=>$match->win,
            ];
        });

        // Fetch champion list from DDragon
        $championData = $this->fetchDdragon();
        $championMap = [];
        foreach ($championData as $champion) {
            $championMap[(int)$champion['key']] = [
                'name' => $champion['id'],
                'image' => "https://ddragon.leagueoflegends.com/cdn/15.10.1/img/champion/{$champion['id']}.png"
            ];
        }

        // We know that hardcoding this is not the right way,
        // but Riots way of structuring their data gave us no choice but to do it like this:
        // This is because in Riots jSon the summonerspell comes out as an integer,
        // but we need the string name from them to get the images, so we have converted them here:
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
            'championMap' => $championMap,
            'matches' => $groupedMatches,
            'groupedRankedHistory' => $groupedRankedHistory,
            'freeChampions' => $freeChampions,
            'summonerSpellMap' => $summonerSpellMap

        ]);
    }
}
