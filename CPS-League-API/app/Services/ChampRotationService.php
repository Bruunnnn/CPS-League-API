<?php

namespace App\Services;

use App\Models\Champion;
use App\Models\ChampionRotation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use function Termwind\renderUsing;

class ChampRotationService
{
    protected string $riotApi;
    protected string $region = 'euw1';

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

    public function connectChampRotationNewPlayers()
    {
        $url = "https://{$this->region}.api.riotgames.com/lol/platform/v3/champion-rotations";

        $response = Http::withoutVerifying()->get($url, [
            'api_key' => config('services.riot.key')
        ]);

        return $response->json() ? $response->json() : null;

    }


    public function getChampRotationNewPlayers()
    {
        $rotationReponse = $this->connectChampRotationNewPlayers();
        $freeIds = $rotationReponse['freeChampionIds'] ?? [];
        $ddragonResponse = Http::withoutVerifying()->get("https://ddragon.leagueoflegends.com/cdn/15.10.1/data/en_US/champion.json");
        $championList = $ddragonResponse->json()['data'] ?? [];
        $championMap = collect($championList)->mapWithKeys(function ($champ){
            return[(int)$champ['key'] => [
                'name' => $champ['name'],
                'image'=> "https://ddragon.leagueoflegends.com/cdn/15.10.1/data/en_US/{$champ['id']}.png",
                'title' => $champ['title']
            ]];
        });
        $freeChampions = collect($freeIds)->map(function ($id) use ($championMap) {
            return $championMap[$id] ?? ['name' => 'Unknown', 'image' => '', 'title' => 'Unknown'];
        });

        return $freeChampions;
    }


    // Try to fix this next
    public function storeChampsForNewPlayers(): ?ChampionRotation
    {
        $rotationData = $this->connectChampRotationNewPlayers();
        if (!$rotationData || !isset($rotationData['freeChampionIdsForNewPlayers'])) {
            return null;
        }
        return ChampionRotation::create([
                'freeChampionIds' => $rotationData['freeChampionIds'],
                'freeChampionIdsForNewPlayers' => $rotationData['freeChampionIdsForNewPlayers'],
                'maxNewPlayerLevel' => $rotationData['maxNewPlayerLevel'] ?? null,
            ]);
    }

    public function getCurrentFreeChampions()
    {
        $rotation = ChampionRotation::latest()->first();

        if (!$rotation) {
            return collect();
        }
        $freeChampionIds = $rotation->freeChampionIds ?? [];

        return Champion::whereIn('key',collect($freeChampionIds)->map(fn($id) => (string) $id))->get();

    }


}
