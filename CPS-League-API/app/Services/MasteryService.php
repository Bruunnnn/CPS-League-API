<?php
namespace App\Services;

use App\Models\Mastery;
use Illuminate\Support\Facades\Http;

class MasteryService
{
    protected string $riotApi;
    protected string $region = 'euw1';

    // Response recipe
    public function returnResponse($url) {
        return Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);
    }

    public function __construct()
    {
        $this->riotApi = config('services.riot.key');
    }

    public function getChampionMastery(string $puuid): ?array
    {
        $url = "https://{$this->region}.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-puuid/{$puuid}";

        $response = $this->returnResponse($url);
        return $response->successful() ? $response->json() : null;
    }

    public function storeTopChampionMastery(string $puuid): ?array
    {
        $masteries = $this->getChampionMastery($puuid);
        if (empty($masteries) || !is_array($masteries)) {
            return null;
        }
        $topMastery = array_slice($masteries, 0, 30);
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
}
