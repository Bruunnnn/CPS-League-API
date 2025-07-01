<?php
namespace App\Services;

use App\Models\Summoner;
use App\Models\Ranked;
use Illuminate\Support\Facades\Http;

class RankedService extends GeneralService
{

    public function getRankedBySummonerId(string $puuid): ?array
    {
        $url = "https://{$this->region}.api.riotgames.com/lol/league/v4/entries/by-puuid/{$puuid}";

        $response = $this->returnResponse($url);

        return $response->successful() ? $response->json() : null;
    }
    public function storeRankedData(string $puuid): array
    {
        $summoner = Summoner::where('puuid', $puuid)->first();

        if (!$summoner) {
            return [];
        }
        $rankedData = $this->getRankedBySummonerId($summoner->puuid);

        if (!$rankedData) {
            return [];
        }

        foreach ($rankedData as $entry) {
            Ranked::updateOrCreate(
                [
                    'puuid' => $puuid,
                    'queueType' => $entry['queueType'],
                ],
                [
                    'tier' => $entry['tier'] ?? 'UNRANKED',
                    'rank' => $entry['rank'] ?? '-',
                    'wins' => $entry['wins'] ?? 0,
                    'leaguePoints' => $entry['leaguePoints'] ?? 0,
                    'losses' => $entry['losses'] ?? 0,
                ]
            );
        }

        return $rankedData;
    }
}
