<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;


class RiotService
{

    protected $riotApi;
    // Which region we're fetching data from
    protected $region = 'euw1';

    // Double "_" cause it is a magic method
    public function __construct()
    {
        $this->riotApi = config('services.riot.key');
        //dd($this->riotApi);
    }

    public function getSummonerByName($gameName, $tagLine)
    {
        // Riot has provided the following url for fetching summoner name
        $url = "https://europe.api.riotgames.com/riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}";

        // Riot requires this header
        $response = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
            //withoutVerifying disables the SSL certification, should not be done if it´s going out to production.
        ])->withoutVerifying()->get($url);
        return $response->json();
    }

    public function getMatchHistory($puuid, $count)
    {
        $matchIds = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
            ////withoutVerifying disables the SSL certification, should not be done if it´s going out to production.
        ])->withoutVerifying()->get("https://europe.api.riotgames.com/lol/match/v5/matches/by-puuid/{$puuid}/ids", [
            'count' => $count,
        ])->json();

        $matches = [];

        foreach ($matchIds as $matchId) {
            $matchData = Http::withHeaders([
                'X-Riot-Token' => $this->riotApi,
                ////withoutVerifying disables the SSL certification, should not be done if it´s going out to production.
            ])->withoutVerifying()->get("https://europe.api.riotgames.com/lol/match/v5/matches/{$matchId}")->json();

            $matches[] = $matchData;
        }

        return $matches;

    }

    public function getSummonerByPuuid($puuid)
    {
        $url = "https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/{$puuid}";
        $response = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);

        return $response->json();

    }
    public function getRankedBySummonerId($summoner_id)
    {
        $url = "https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/{$summoner_id}";

        $response = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);

        return $response->json();
    }
    public function getChampionMastery($puuid)
    {
        $url = "https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-puuid/{$puuid}";

        $response = Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);

        return $response->json(); // returns array of mastery data
    }




    // Storing in the database
    public function storeSummoner(Request $request)
    {
        $riot = new RiotService();

        $accountInfo = $riot->getSummonerByName($request->gameName, $request->tagLine);
        $summonerInfo = $riot->getSummonerByPuuid($accountInfo['puuid']);

        $summoner = Summoner::updateOrCreate(
            ['puuid' => $summonerInfo['puuid']],
            [
                'name' => $summonerInfo['name'],
                'summoner_id' => $summonerInfo['id'],
                'account_id' => $summonerInfo['accountId'] ?? null,
                'profile_icon_id' => $summonerInfo['profileIconId'],
                'summoner_level' => $summonerInfo['summonerLevel'],
            ]
        );

        return response()->json($summoner);
    }

}
