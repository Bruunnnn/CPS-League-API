<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;


class RiotService {

    protected $riotApi;
    // Which region we're fetching data from
    protected $region = 'euw1';

    // Double "_" cause it is a magic method
    public function __construct() {
        $this->riotApi = config('services.riot.key');
        //dd($this->riotApi);
    }

    public function getSummonerByName($gameName, $tagLine) {
        // Riot has provided the following url for fetching summoner name
        $url = "https://europe.api.riotgames.com/riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}";

        // Riot requires this header
        $response = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->get($url);
        return $response->json();
    }

    public function getMatchHistory($puuid, $count) {
        $matchIds = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->get("https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids", [
            'count' => $count,
        ])->json();

        $matches = [];

        foreach ($matchIds as $matchId) {
            $matchData = Http::withHeaders([
                'X-Riot-Token' => $this->riotApi,
            ])->get("https://europe.api.riotgames.com/lol/match/v5/matches/{$matchId}")->json();

            $matches[] = $matchData;
        }

        return $matches;
    }


}
