<?php

namespace App\Services;

use App\Models\MatchHistory;
use Illuminate\Support\Facades\Http;
class MatchHistoryService extends GeneralService
{

    // Fetches the complete amount of meta-data from Leagues API
    // Count can be increased / decreased depending on the number of games we want to display
    public function getMatchHistory(string $puuid, int $count = 30): array
    {
        $matchIds = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get("https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids", [
            'count' => $count,
        ])->json();

        $matches = [];

        foreach ($matchIds as $matchId) {
            $matchData = Http::withHeaders([
                'X-Riot-Token' => $this->riotApi,
            ])->withoutVerifying()->get("https://europe.api.riotgames.com/lol/match/v5/matches/{$matchId}")->json();

            $matches[] = $matchData;
        }
        return $matches;


    }

    // Takes the meta-data and sorts in the data-elements we want to manipulate
    public function storeMatchHistory(string $puuid): array
    {
        // Returns Json matchHistory
        $matches = $this->getMatchHistory($puuid);
        foreach ($matches as $match) {
            if (!isset($match['info']['participants'])) {
                continue;
            }
            foreach ($match['info']['participants'] as $participant) {
                MatchHistory::updateOrCreate(
                    [
                        'puuid' => $participant['puuid'],
                        'gameId' => $match['info']['gameId'],
                    ],
                    [
                        'mapId' => $match['info']['mapId']== 0,
                        'queueId' => $match['info']['queueId'] ?? 0,
                        'endGameTimestamp' => $match['info']['gameEndTimestamp'],
                        'win' => $participant['win'],
                        'riotIdGameName' => $participant['riotIdGameName'],
                        'riotIdTagline'=> $participant['riotIdTagline'],
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
                        'profileIcon'=> $participant['profileIcon'] ?? 0,
                    ]
                );
            }
        }
        return $matches;
    }
}
