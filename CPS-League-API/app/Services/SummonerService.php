<?php
namespace App\Services;

use App\Models\Summoner;
use Illuminate\Support\Facades\Http;

class SummonerService
{
    protected string $riotApi;
    protected string $region = 'euw1';
    protected string $europeRegion = 'europe';

    // Response recipe
    public function returnResponse($url) {
        return Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);
    }

    // Double "_" cause it is a magic method
    public function __construct()
    {
        $this->riotApi = config('services.riot.key');
    }

    // Fetch summoner from name
    public function getSummonerByName(string $gameName, string $tagLine): ?array
    {
        $url = "https://{$this->europeRegion}.api.riotgames.com/riot/account/v1/accounts/by-riot-id/{$gameName}/{$tagLine}";

        $response = $this->returnResponse($url);

        return $response->successful() ? $response->json() : null;
    }

    // Fetch summoner from puuid
    public function getSummonerByPuuid(string $puuid): ?array
    {
        $url = "https://{$this->region}.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/{$puuid}";

        $response = $this->returnResponse($url);

        return $response->successful() ? $response->json() : null;
    }

    public function storeSummoner(string $riotId): ?summoner
    {
        $parts = explode('-', $riotId);
        if (count($parts) !== 2) {
            return response("Invalid riot ID format", 400);
        }
        [$gameName, $tagLine] = $parts;

        $accountInfo = $this->getSummonerByName($gameName, $tagLine);
        if (!$accountInfo || !isset($accountInfo['puuid'])) return null;

        $summonerInfo = $this->getSummonerByPuuid($accountInfo['puuid']);

        if (!$summonerInfo) return null;

        return Summoner::updateOrCreate(
            ['puuid' => $summonerInfo['puuid']],
            [
                'game_name' => $gameName,
                'tag_line' => $tagLine,
                'summoner_id' => $summonerInfo['id'],
                'account_id' => $summonerInfo['accountId'],
                'profile_icon_id' => $summonerInfo['profileIconId'],
                'summoner_level' => $summonerInfo['summonerLevel'],
            ]
        );
    }

    public function getQueueMappings()
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
}

